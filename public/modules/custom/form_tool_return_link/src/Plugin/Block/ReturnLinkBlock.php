<?php

namespace Drupal\form_tool_return_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\webform_formtool_handler\Plugin\WebformHandler\FormToolWebformHandler;

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
   * {@inheritdoc}
   */
  public function build() {

    $returnLinkUrl =  'https://hel.fi/fi';

    return [
      '#theme' => 'return_link_block',
      '#return_link_text' => t('Back to service details'),
      '#return_link_url' => $returnLinkUrl,
    ];
  }

}
