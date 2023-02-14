<?php

namespace Drupal\form_tool_noscript\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a noscripttag block.
 *
 * @Block(
 *   id = "formtool_noscripttag",
 *   admin_label = @Translation("NoScript -Tag"),
 *   category = @Translation("FormTool")
 * )
 */
class FormToolNoScriptTagBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $renderable = [
      '#theme' => 'block__form_tool_noscript',
    ];
    return $renderable;
  }

}
