<?php

namespace Drupal\form_tool_return_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Return link' Block.
 *
 * @Block(
 *   id = "return_link_block",
 *   admin_label = @Translation("Return link block"),
 *   category = @Translation("Return link"),
 * )
 */
class ReturnLinkBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected LanguageManagerInterface $languageManager;

  /**
   * Reguest stack.
   *
   * @var \Drupal\Core\Http\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * The state store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected StateInterface $state;

  /**
   * Constructs a new SwitchUserBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   The current route match.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   Language manager.
   * @param \Drupal\Core\Http\RequestStack $request_stack
   *   Reguest stack.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state store.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $current_route_match, LanguageManagerInterface $language_manager, RequestStack $request_stack, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $current_route_match;
    $this->languageManager = $language_manager;
    $this->requestStack = $request_stack;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('language_manager'),
      $container->get('request_stack'),
      $container->get('state'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $params = $this->routeMatch->getParameters()->all();
    $currentLanguage = $this->languageManager->getCurrentLanguage()->getId();

    // Text values for the link text.
    $returnLinkText = $this->t('Back to Hel.fi front page');
    $serviceDetailsLinkText = $this->t('Back to service details');

    // Default value for returnLinkUrl to hel.fi front page.
    $returnLinkUrl = 'https://www.hel.fi/' . $currentLanguage . '/';

    // Submit error page.
    if (empty($params) && !empty($this->requestStack->getCurrentRequest()->get('backlink_id'))) {
      $returnLinkText = $serviceDetailsLinkText;
      $returnLinkUrl = $this->state->get($this->requestStack->getCurrentRequest()->get('backlink_id'));
    }

    // Webform node page.
    if (array_key_exists('node', $params)) {
      $node = $params['node'];
      if (!$node->get('field_url_to_form_service')->isEmpty()) {
        $returnLinkText = $serviceDetailsLinkText;
        $returnLinkUrl = $node->get('field_url_to_form_service')->first()->getUrl()->getUri();
      }
    }

    // Thank you page, View submission page.
    if (array_key_exists('submission_id', $params)) {
      $parts = explode('-', $params['submission_id']);
      $id = $parts['0'] . '-' . $parts['1'] . '-' . $currentLanguage;
      $returnLinkText = $serviceDetailsLinkText;
      $returnLinkUrl = $this->state->get($id);
    }

    // If for some reason we don't get url.
    if ($returnLinkUrl == NULL) {
      // Default value for returnLinkUrl to hel.fi front page.
      $returnLinkUrl = 'https://www.hel.fi/' . $currentLanguage . '/';
    }

    return [
      '#theme' => 'return_link_block',
      '#return_link_text' => $returnLinkText,
      '#return_link_url' => $returnLinkUrl,
    ];
  }

}
