<?php

namespace Drupal\form_tool_profile_data\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\helfi_helsinki_profiili\TokenExpiredException;
use Drupal\webform\Element\WebformCompositeBase;
use Drupal\form_tool_profile_data\Plugin\WebformElement\FormToolProfileData as ProfileDataElement;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a 'webform_example_composite'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. webform_address)
 *
 * @FormElement("form_tool_profile_data")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\form_tool_profile_data\Element\FormToolProfileData
 */
class FormToolProfileData extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return parent::getInfo() + ['#theme' => 'form_tool_profile_data'];
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {
    $elements = [];

    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $hpud */
    $hpud = \Drupal::service('helfi_helsinki_profiili.userdata');

    // If user is not helsinkiproifile user we don't have any user info.
    $currentUserRoles = \Drupal::currentUser()->getRoles();
    if (
      !in_array('helsinkiprofiili_vahva', $currentUserRoles) &&
      !in_array('helsinkiprofiili_heikko', $currentUserRoles)) {
      return [];
    }

    $options = ProfileDataElement::getFieldSelections();

    try {
      $userProfile = $hpud->getUserProfileData();
    }
    catch (TokenExpiredException $e) {
      \Drupal::logger('form_tool_profile_data')->error('Error fetching user profile data: @error', ['@error' => $e->getMessage()]);
    }

    if (!\Drupal::service('router.admin_context')->isAdminRoute()) {
      if (empty($userProfile) || empty($userProfile['myProfile'])) {
        throw new AccessDeniedHttpException('No profile data available');
      }
    }

    $profileRefreshLink = Link::createFromRoute(
      t('Refresh data from Helsinki Profiili'),
      'form_tool_profile_data.refresh_profile_data'
    );

    $elements['refreshLink'] = $profileRefreshLink->toRenderable();

    $authLevel = $hpud->getAuthenticationLevel();

    if ($authLevel == 'strong' && isset($element['#strong'])) {

      $selectedFields = $element['#strong'];

      if ($userProfile === NULL) {
        return $elements;
      }

      if (isset($selectedFields['verifiedFirstName']) && $selectedFields['verifiedFirstName'] !== 0) {
        $elements['verifiedFirstName'] = [
          '#type' => 'textfield',
          '#title' => $options['strong']['verifiedFirstName'],
          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["firstName"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue(
            $userProfile["myProfile"]["verifiedPersonalInformation"]["firstName"] ?: '-'
          ),
          '#required' => TRUE,
        ];
        $elements['verifiedFirstName']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
      }
      if (isset($selectedFields['verifiedLastName']) && $selectedFields['verifiedLastName'] !== 0) {
        $elements['verifiedLastName'] = [
          '#type' => 'textfield',
          '#title' => $options['strong']['verifiedLastName'],
          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["lastName"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue(
            $userProfile["myProfile"]["verifiedPersonalInformation"]["lastName"] ?: '-'
          ),
          '#required' => TRUE,
        ];
        $elements['verifiedLastName']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
      }
      if (isset($selectedFields['verifiedSsn']) && $selectedFields['verifiedSsn'] !== 0) {
        $elements['verifiedSsn'] = [
          '#type' => 'textfield',
          '#title' => $options['strong']['verifiedSsn'],
          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["nationalIdentificationNumber"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue(
            $userProfile["myProfile"]["verifiedPersonalInformation"]["nationalIdentificationNumber"] ?: '-'
          ),
          '#required' => TRUE,
        ];
        $elements['verifiedSsn']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';

      }
      if (isset($selectedFields['verifiedGivenName']) && $selectedFields['verifiedGivenName'] !== 0) {
        $elements['verifiedGivenName'] = [
          '#type' => 'textfield',
          '#title' => $options['strong']['verifiedGivenName'],
          '#value' => $userProfile["myProfile"]["verifiedPersonalInformation"]["givenName"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue(
            $userProfile["myProfile"]["verifiedPersonalInformation"]["givenName"] ?: '-'
          ),
          '#required' => TRUE,
        ];
        $elements['verifiedGivenName']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
      }
      if (isset($selectedFields['verifiedPermanentAddress']) && $selectedFields['verifiedPermanentAddress'] !== 0) {
        $permanentAddress = [
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["streetAddress"],
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postalCode"],
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postOffice"],
        ];
        $elements['verifiedPermanentAddress'] = [
          '#type' => 'textfield',
          '#title' => $options['strong']['verifiedPermanentAddress'],
          '#value' =>
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["streetAddress"] . ', ' .
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postalCode"] . ', ' .
          $userProfile["myProfile"]["verifiedPersonalInformation"]["permanentAddress"]["postOffice"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue($permanentAddress ?: '-'),
          '#required' => TRUE,
        ];
        $elements['verifiedPermanentAddress']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
      }
    }

    if ($authLevel == 'weak' && isset($element['#weak'])) {
      $selectedFields = $element['#weak'];

      if (isset($selectedFields['primaryAddress']) && $selectedFields['primaryAddress'] !== 0) {
        $primaryAddress = [
          $userProfile["myProfile"]["primaryAddress"]["address"],
          $userProfile["myProfile"]["primaryAddress"]["postalCode"],
          $userProfile["myProfile"]["primaryAddress"]["city"],
          $userProfile["myProfile"]["primaryAddress"]["countryCode"],
        ];
        $elements['primaryAddress'] = [
          '#type' => 'textfield',
          '#title' => $options['weak']['primaryAddress'],
          '#value' =>
          $userProfile["myProfile"]["primaryAddress"]["address"] . ', ' .
          $userProfile["myProfile"]["primaryAddress"]["postalCode"] . ', ' .
          $userProfile["myProfile"]["primaryAddress"]["city"] . ', ' .
          $userProfile["myProfile"]["primaryAddress"]["countryCode"],
          '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
          '#description' => self::handleTextValue($primaryAddress ?: '-'),
          '#required' => TRUE,
        ];
        $elements['primaryAddress']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
      }
    }

    // Move this outside of those ifs so that snyk doesn't go crazy.
    if (isset($selectedFields['primaryEmail']) &&
      $selectedFields['primaryEmail'] !== 0
    ) {
      $elements['primaryEmail'] = [
        '#type' => 'textfield',
        '#title' => $options['weak']['primaryEmail'],
        '#value' => $userProfile["myProfile"]["primaryEmail"]["email"] ?? '-',
        '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
        '#description' => self::handleTextValue($userProfile["myProfile"]["primaryEmail"]["email"] ?? '-'),
        '#required' => TRUE,
      ];
      $elements['primaryEmail']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
    }
    if (isset($selectedFields['primaryPhone']) &&
      $selectedFields['primaryPhone'] !== 0
    ) {
      $elements['primaryPhone'] = [
        '#type' => 'textfield',
        '#title' => $options['weak']['primaryPhone'],
        '#value' => $userProfile["myProfile"]["primaryPhone"]["phone"] ?? '-',
        '#description' => self::handleTextValue($userProfile["myProfile"]["primaryPhone"]["phone"] ?? '-'),
        '#attributes' => ['readonly' => 'readonly', 'style' => 'display:none'],
        '#required' => TRUE,
        '#element_validate' => [
          [static::class, 'validatePhoneNumber'],
        ],
      ];
      $elements['primaryPhone']['#wrapper_attributes']['class'][] = 'form_tool__prefilled_field';
    }

    return $elements;
  }

  /**
   * Performs the after_build callback.
   */
  public static function afterBuild(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['disabled'] = [
      [':input[name="' . $composite_name . '[first_name]"]' => ['empty' => TRUE]],
      [':input[name="' . $composite_name . '[last_name]"]' => ['empty' => TRUE]],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Handle text value for the description fields.
   *
   * @param string|array $textValue
   *   String or array containing text values.
   *
   * @return array
   *   Returns render array.
   */
  private static function handleTextValue(string|array $textValue) : array {
    $description = is_array($textValue)
      ? implode(', ', $textValue)
      : $textValue;

    return [
      '#theme' => 'profile_data_icon',
      '#text_value' => $description,
    ];
  }

  /**
   * Custom validator to validate primary phone number.
   *
   * @param array $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public static function validatePhoneNumber(array $element, FormStateInterface $form_state) {
    $value = $element['#value'] ?? NULL;

    if (!empty($value)) {
      $valid = preg_match("/^\+[\d]+\b/", $value);
      if (!$valid) {
        $form_state->setError($element, t('%name is not a valid number.', [
          '%name' => t('Primary phone'),
        ]));
      }
    }
  }

}
