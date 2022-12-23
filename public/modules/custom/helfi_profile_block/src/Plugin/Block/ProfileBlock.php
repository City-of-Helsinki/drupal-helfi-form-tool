<?php

namespace Drupal\helfi_profile_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a Profile Block.
 *
 * @Block(
 *   id = "profile_block",
 *   admin_label = @Translation("Profile block"),
 *   category = @Translation("Profile"),
 * )
 */
class ProfileBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $logoutUrl = Url::fromRoute('user.logout');

    return [
      '#theme' => 'profile_block',
      '#display_name' => 'Kalevi-Markku',
      '#full_name' => 'Kalevi-Markku Meikäläinen',
      '#email' => 'matti.meikalainen@hel.fi',
      '#logout_url' => $logoutUrl,
    ];
  }

}
