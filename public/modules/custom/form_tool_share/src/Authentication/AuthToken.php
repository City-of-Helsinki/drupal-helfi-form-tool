<?php

namespace Drupal\form_tool_share\Authentication;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\UserSession;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use Drupal\user\Entity\User;
use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Authentication provider to validate requests with token in header.
 */
class AuthToken implements AuthenticationProviderInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The user auth service.
   *
   * @var \Drupal\user\UserAuthInterface
   */
  protected $userAuth;

  /**
   * The flood service.
   *
   * @var \Drupal\Core\Flood\FloodInterface
   */
  protected $flood;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Access to profile data.
   *
   * @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData
   */
  protected HelsinkiProfiiliUserData $helsinkiProfiiliUserData;

  /**
   * The logger channel factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * Constructs a HTTP basic authentication provider object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $helsinkiProfiiliUserData
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              EntityTypeManagerInterface $entity_type_manager,
                              HelsinkiProfiiliUserData $helsinkiProfiiliUserData,
                              LoggerChannelFactoryInterface $logger_factory,
  ) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->helsinkiProfiiliUserData = $helsinkiProfiiliUserData;
    $this->logger = $logger_factory->get('form_tool_share');
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request): bool {
    if (\Drupal::currentUser()->isAnonymous()) {
      return $request->headers->has('X-Auth-Token');
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(Request $request): UserSession|AccountInterface|NULL {

    $token = $request->headers->get('X-Auth-Token');

    $oidc = new OpenIDConnectClient('https://tunnistamo.test.hel.ninja',
      'lomaketyokalu-ui-dev',
      '0cf09212235cc0fa16f6b7c3194fc3bde81c7d920ff3b2773a047a7b');

    try {
      if ($oidc->canVerifySignatures() && $oidc->verifyJWTsignature($token)) {
        // Signature verified.
        $tokenData = $this->helsinkiProfiiliUserData->parseToken($token);
        $accounts = $this->entityTypeManager->getStorage('user')
          ->loadByProperties(['mail' => $tokenData['email'], 'status' => 1]);

        $account = reset($accounts);

        if ($account) {
          $uid = $account->id();
          $user = User::load($uid);
        }
        else {
          $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

          $user = User::create();
          $user->setPassword('adgf');
          $user->enforceIsNew();
          $user->setEmail($tokenData['email']);
          // This username must be unique and accept only a-Z,0-9, - _ @ .
          $user->setUsername($tokenData['name']);
          $user->set("init", 'mail');
          $user->set("langcode", $language);
          $user->set("preferred_langcode", $language);
          $user->set("preferred_admin_langcode", $language);
          $user->activate();

        }

        if ($tokenData["loa"] === 'substantial') {
          $user->addRole('helsinkiprofiili_vahva');
        }
        else {
          $user->removeRole('helsinkiprofiili_vahva');
          $user->addRole('helsinkiprofiili_heikko');
        }

        // Save user account.
        $user->save();

        user_login_finalize($user);

        return $user;

      }
      return NULL;
    }
    catch (OpenIDConnectClientException | InvalidPluginDefinitionException | PluginNotFoundException | \Exception $e) {
      return NULL;
    }
  }

}
