<?php

namespace Drupal\form_tool_contact_info\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformCompositeBase;

/**
 * Provides a 'form_tool_contact_info'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. webform_address)
 *
 * @FormElement("form_tool_contact_info")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\form_tool_contact_info\Element\FormToolContactInfo
 */
class FormToolContactInfo extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $info = parent::getInfo();
    $class = get_class($this);
    $info['#pre_render'] = [
      [$class, 'preRenderCompositeFormElement'],
    ];
    $info['#theme'] = 'form_tool_contact_info';
    return parent::getInfo() + $info;
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    $maxlenghtLongField = 100;

    $elements = [];
    $elements['Info'] = [
      '#type' => 'item',
      '#theme' => 'notification_content',
      '#notice_value' => t('Selecting a delivery method may prompt further questions'),
    ];
    $elements['Toimitustapa: Email'] = [
      '#type' => 'checkbox',
      '#title' => t('Email'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Postitoimitus'] = [
      '#type' => 'checkbox',
      '#title' => t('Postal Delivery'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Postiennakko'] = [
      '#type' => 'checkbox',
      '#title' => t('Cash on Delivery'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Nouto'] = [
      '#type' => 'checkbox',
      '#title' => t("Collect. The document will be picked up from the Education Division's archive. Töysänkatu 2 D, 00510 Helsinki."),
      '#title_display' => 'before',
    ];
    $elements['delivery_method'] = [
      '#type' => 'radios',
      '#title' => t('Delivery'),
      '#title_display' => 'before',
      '#options' => [
        'email' => t('Email Address'),
        'postal' => t('Postal Delivery'),
        'cod' => t('Cash on Delivery'),
        'pickup' => t("Collect. The document will be picked up from the Education Division's archive. Töysänkatu 2 D, 00510 Helsinki."),
      ],
      '#required' => TRUE,
      '#after_build' => [[get_called_class(), 'deliveryOptions']],
    ];
    $elements['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#autocomplete' => 'given-name',
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#autocomplete' => 'family-name',
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['street_address'] = [
      '#type' => 'textfield',
      '#title' => t('Street Address'),
      '#autocomplete' => 'address-level2',
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['zip_code'] = [
      '#type' => 'textfield',
      '#title' => t('Zip Code'),
      '#autocomplete' => 'postal-code',
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['city'] = [
      '#type' => 'textfield',
      '#title' => t('City'),
      '#autocomplete' => 'address-level1 city',
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['phone_number'] = [
      '#type' => 'textfield',
      '#title' => t('Phone Number'),
      '#autocomplete' => 'tel',
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'postalAddress']],
    ];
    $elements['cod'] = [
      '#type' => 'item',
      '#markup' => t('Cash on delivery price is 9,20 €'),
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
    ];
    $elements['cod_first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#autocomplete' => 'shipping given-name',
      '#maxlength' => $maxlenghtLongField,
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['cod_last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#autocomplete' => 'shipping family-name',
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#maxlength' => $maxlenghtLongField,
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['cod_street_address'] = [
      '#type' => 'textfield',
      '#title' => t('Street Address'),
      '#autocomplete' => 'shipping address-level2',
      '#maxlength' => $maxlenghtLongField,
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['cod_zip_code'] = [
      '#type' => 'textfield',
      '#title' => t('Zip Code'),
      '#autocomplete' => 'shipping postal-code',
      '#maxlength' => 5,
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['cod_city'] = [
      '#type' => 'textfield',
      '#title' => t('City'),
      '#autocomplete' => 'shipping address-level1 city',
      '#maxlength' => $maxlenghtLongField,
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['cod_phone_number'] = [
      '#type' => 'textfield',
      '#title' => t('Phone Number'),
      '#autocomplete' => 'shipping tel',
      '#maxlength' => 20,
      // Use #after_build to add #states.
      '#after_build' => [[get_called_class(), 'codPostalAddress']],
      '#states' => [
        'required' => [
          ':input[data-drupal-selector="edit-valitse-toimitustapa-delivery-method-cod"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email Address'),
      '#autocomplete' => 'shipping email',
      '#after_build' => [[get_called_class(), 'email']],
    ];
    $elements['Postiennakko -teksti'] = [
      '#type' => 'item',
      '#title' => t('Cash on delivery price is 9,20 €'),
    ];

    return $elements;
  }

  /**
   * Performs the after_build callback.
   */
  public static function deliveryOptions(array $element, FormStateInterface $form_state) {
    return $element;
  }

  /**
   * Performs the after_build callback.
   */
  public static function email(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'email']],
    ];
    $element['#states']['required'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'email']],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Performs the after_build callback.
   */
  public static function postalAddress(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'postal']],
    ];
    $element['#states']['required'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'postal']],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Performs the after_build callback.
   */
  public static function codPostalAddress(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'cod']],
    ];
    $element['#states']['required'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'cod']],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Performs the after_build callback.
   */
  public static function pickup(array $element, FormStateInterface $form_state) {
    // Add #states targeting the specific element and table row.
    preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
    $composite_name = $match[1];
    $element['#states']['visible'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'pickup']],
    ];
    $element['#states']['required'] = [
      [':input[name="' . $composite_name . '[delivery_method]"]' => ['value' => 'pickup']],
    ];
    // Add .js-form-wrapper to wrapper (ie td) to prevent #states API from
    // disabling the entire table row when this element is disabled.
    $element['#wrapper_attributes']['class'][] = 'js-form-wrapper';
    return $element;
  }

  /**
   * Performs the after_build callback.
   */
  public static function preRenderWebformCompositeFormElement($element) {
    $element = parent::preRenderWebformCompositeFormElement($element);

    if ($element['Toimitustapa: Email']['#access'] != 1) {
      unset($element['delivery_method']['email']);
    }
    else {
      $element['delivery_method']['email']['#title'] = $element['Toimitustapa: Email']['#title'];
    }
    unset($element['Toimitustapa: Email']);
    if ($element['Toimitustapa: Postitoimitus']['#access'] != 1) {
      unset($element['delivery_method']['postal']);
    }
    else {
      $element['delivery_method']['postal']['#title'] = $element['Toimitustapa: Postitoimitus']['#title'];
    }
    unset($element['Toimitustapa: Postitoimitus']);
    if ($element['Toimitustapa: Postiennakko']['#access'] != 1) {
      unset($element['delivery_method']['cod']);
    }
    else {
      $element['delivery_method']['cod']['#title'] = $element['Toimitustapa: Postiennakko']['#title'];
    }
    unset($element['Toimitustapa: Postiennakko']);
    if ($element['Toimitustapa: Nouto']['#access'] != 1) {
      unset($element['delivery_method']['pickup']);
    }
    else {
      $element['delivery_method']['pickup']['#title'] = $element['Toimitustapa: Nouto']['#title'];
    }
    $element['delivery_method']['#title'] = $element['#title'];
    unset($element['Toimitustapa: Nouto']);
    if ($element['Postiennakko -teksti']['#title'] != '') {
      $element['cod']['#markup'] = $element['Postiennakko -teksti']['#title'];
    }
    unset($element['Postiennakko -teksti']);

    $elements['Toimitustapa: Email'] = [
      '#type' => 'checkbox',
      '#title' => t('Email'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Postitoimitus'] = [
      '#type' => 'checkbox',
      '#title' => t('Postal Delivery'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Postiennakko'] = [
      '#type' => 'checkbox',
      '#title' => t('Cash on Delivery'),
      '#title_display' => 'before',
    ];
    $elements['Toimitustapa: Nouto'] = [
      '#type' => 'checkbox',
      '#title' => t("Collect. The document will be picked up from the Education Division's archive. Töysänkatu 2 D, 00510 Helsinki."),
      '#title_display' => 'before',
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function processWebformComposite(&$element, FormStateInterface $form_state, &$complete_form): array {
    // Process element.
    $element = parent::processWebformComposite($element, $form_state, $complete_form);

    // Load submission & data.
    $submission = $form_state->getFormObject()->getEntity();
    $submissionData = $submission->getData();

    // Loop data & make sure it's set properly with #default_values
    // process only 2 levels,.
    // @todo check if more dynamic parsing is necessary with address forms.
    foreach ($submissionData as $key => $value) {
      if (is_array($value)) {
        foreach ($value as $key2 => $value2) {
          if (!is_array($value2)) {
            if (isset($element[$key2])) {
              $element[$key2]['#default_value'] = $value2;
            }
          }
        }
      }
      else {
        $element[$key]['#default_value'] = $value;
      }
    }

    return $element;
  }

}
