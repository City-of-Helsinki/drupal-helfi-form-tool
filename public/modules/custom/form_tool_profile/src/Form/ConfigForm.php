<?php

namespace Drupal\form_tool_profile\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Config form for form_tool_profile module.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * The language manager object.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(LanguageManagerInterface $language_manager) {
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'form_tool_profile.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_tool_profile_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $language = $this->languageManager->getCurrentLanguage()->getId();
    $config = $this->languageManager->getLanguageConfigOverride($language, 'form_tool_profile.settings');

    $form['login_info'] = [
      '#type' => 'details',
      '#title' => $this->t('Login info (@lang)', ['@lang' => $language]),
    ];

    $form['login_info']['login_info_no_auth'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Login info for login page'),
      '#default_value' => $config->get('login_info_no_auth'),
    ];

    $form['login_info']['login_info_weak_auth'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Login info when the form requires weak authentication'),
      '#default_value' => $config->get('login_info_weak_auth'),
    ];

    $form['login_info']['login_info_strong_auth'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Login info when the form requires weak authentication'),
      '#default_value' => $config->get('login_info_strong_auth'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $language = $this->languageManager->getCurrentLanguage()->getId();

    $this->languageManager->getLanguageConfigOverride($language, 'form_tool_profile.settings')
      ->set('login_info_no_auth', $form_state->getValue('login_info_no_auth')['value'])
      ->set('login_info_weak_auth', $form_state->getValue('login_info_weak_auth')['value'])
      ->set('login_info_strong_auth', $form_state->getValue('login_info_strong_auth')['value'])
      ->save();
  }

}
