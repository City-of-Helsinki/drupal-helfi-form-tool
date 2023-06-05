<?php

namespace Drupal\form_tool_share\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\webform_formtool_handler\Plugin\WebformHandler\FormToolWebformHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Form Tool Share routes.
 */
class FormCompletionController extends ControllerBase {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $account;

  /**
   * The controller constructor.
   */
  public function __construct() {
    $this->account = \Drupal::currentUser();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static();
  }

  /**
   * Builds the response.
   *
   * @param string $submission_id
   *   Form / submission id.
   *
   * @return array
   *   Render array.
   *
   * @throws \Drupal\helfi_atv\AtvDocumentNotFoundException
   * @throws \Drupal\helfi_atv\AtvFailedToConnectException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function build(string $submission_id): array {
    /** @var \Drupal\webform\Entity\WebformSubmission $entity */
    $entity = FormToolWebformHandler::submissionObjectAndDataFromFormId($submission_id, 'view');

    $webformSettings = $entity->getWebform()->getSettings();

    $confirmationTitle = t('Thank you');
    $confirmationMessage = [
      '#markup' => '',
    ];

    if (array_key_exists('confirmation_title', $webformSettings) && !empty($webformSettings['confirmation_title'])) {
      $confirmationTitle = $webformSettings['confirmation_title'];
    }

    if (array_key_exists('confirmation_message', $webformSettings) && !empty($webformSettings['confirmation_message'])) {
      $confirmationMessage = [
        '#markup' => $webformSettings['confirmation_message'],
      ];
    }

    $urlToSubmission = Url::fromRoute(
      'form_tool_share.view_submission',
      ['submission_id' => $submission_id],
      [
        'attributes' => [
          'data-drupal-selector' => 'form-submitted-ok',
        ],
      ]
    );

    $urlToLogout = Url::fromRoute('user.logout.http');

    return [
      '#theme' => 'form_tool_share_completion',
      '#submission_id' => $submission_id,
      '#submission_data' => $entity->getData(),
      '#confirmation_title' => $confirmationTitle,
      '#confirmation_message' => $confirmationMessage,
      '#url_to_submission' => $urlToSubmission,
      '#url_to_logout' => $urlToLogout,
    ];
  }

}
