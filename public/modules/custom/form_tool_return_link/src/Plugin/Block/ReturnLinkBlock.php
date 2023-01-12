<?php

namespace Drupal\form_tool_return_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'Return link' Block.
 *
 * @Block(
 *   id = "return_link_block",
 *   admin_label = @Translation("Return link block"),
 *   category = @Translation("Return link"),
 * )
 */
class ReturnLinkBlock extends BlockBase {

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
    $params = \Drupal::routeMatch()->getParameters()->all();
    $currentLanguage = \Drupal::languageManager()->getCurrentLanguage()->getId();

    // Text values for the link text.
    $returnLinkText = $this->t('Back to Hel.fi front page');
    $serviceDetailsLinkText = $this->t('Back to service details');

    // Default value for returnLinkUrl to hel.fi front page.
    $returnLinkUrl = 'https://www.hel.fi/' . $currentLanguage . '/';

    // Submit error page.
    if (empty($params) && !empty(\Drupal::request()->get('backlink_id'))) {
      $returnLinkText = $serviceDetailsLinkText;
      $returnLinkUrl = \Drupal::state()->get(\Drupal::request()->get('backlink_id'));
    }

    // Webform node page.
    if (array_key_exists('node', $params)) {
      $node = $params['node'];
      $returnLinkText = $serviceDetailsLinkText;
      $returnLinkUrl = $node->get('field_url_to_form_service')->first()->getUrl()->getUri();
    }

    // Thank you page, View submission page.
    if (array_key_exists('submission_id', $params)) {
      $parts = explode('-', $params['submission_id']);
      $id = $parts['0'] . '-' . $parts['1'] . '-' . $currentLanguage;
      $returnLinkText = $serviceDetailsLinkText;
      $returnLinkUrl = \Drupal::state()->get($id);
    }

    // if for some reason we don't get url.
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
