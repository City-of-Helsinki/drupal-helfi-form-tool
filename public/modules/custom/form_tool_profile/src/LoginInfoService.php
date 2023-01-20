<?php

namespace Drupal\form_tool_profile;

use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Provides service that will get the login info for different login types.
 */
class LoginInfoService {

  const AUTH_NONE   = 0;
  const AUTH_WEAK   = 1;
  const AUTH_STRONG = 2;

  const CONFIG_MAPPING = [
    self::AUTH_NONE   => 'login_info_no_auth',
    self::AUTH_WEAK   => 'login_info_weak_auth',
    self::AUTH_STRONG => 'login_info_strong_auth',
  ];

  /**
   * The language manager object.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs LoginInfoService.
   */
  public function __construct(LanguageManagerInterface $language_manager) {
    $this->languageManager = $language_manager;
  }

  /**
   * Gets login info text for given login type.
   *
   * @param int $loginType
   *   Login type required by the webform.
   *
   * @return string|null
   *   HTML string or NULL if no config was found for some reason.
   */
  public function getLoginInfo($loginType = 0) {
    $loginType = intval($loginType);

    $configuration_key = self::CONFIG_MAPPING[$loginType] ?? NULL;

    if ($configuration_key) {
      $language_id = $this->languageManager->getCurrentLanguage()->getId();
      $config_value = $this->languageManager
        ->getLanguageConfigOverride($language_id, 'form_tool_profile.settings')
        ->get($configuration_key);

      return $config_value;
    }

    return NULL;

  }

}
