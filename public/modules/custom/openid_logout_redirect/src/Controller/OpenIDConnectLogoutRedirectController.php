<?php

namespace Drupal\openid_logout_redirect\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\openid_logout_redirect\Service\RedirectService;

/**
 * Redirect controller class.
 */
class OpenIDConnectLogoutRedirectController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The session object.
   *
   * @var Drupal\openid_logout_redirect\Service\RedirectService
   */
  protected $redirect;

  /**
   * OpenIDConnectLogoutRedirectController constructor.
   *
   * @param Drupal\openid_logout_redirect\Service\RedirectService $redirectService
   *   The redirect service.
   */
  public function __construct(RedirectService $redirectService) {
    $this->redirect = $redirectService;
  }

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The Drupal service container.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('openid_logout_redirect.redirect'),
    );
  }

  /**
   * User logout redirection.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   Redirect response.
   */
  public function logoutRedirect() {
    return $this->redirect->getLogoutRedirectUrl();
  }

}
