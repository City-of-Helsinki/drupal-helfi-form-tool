<?php

namespace Drupal\webform_formtool_handler\Event;

use Drupal\helfi_atv\AtvDocument;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event submission create.
 */
class WebformSubmissionEvent extends Event {

  const SUBMISSION_EVENT = 'webform_formtool_handler.submission.create';

  /**
   * Webform submission object.
   *
   * @var \Drupal\webform\WebformSubmissionInterface
   */
  private $submission;

  /**
   * Atv document object.
   *
   * @var \Drupal\helfi_atv\AtvDocument
   */
  private $atvDocument;

  /**
   * Submission id.
   *
   * @var string
   */
  private $submissionId;

  /**
   * Construct a new event.
   *
   * @param Drupal\webform\WebformSubmissionInterface $webform_submission
   *   Webform submission.
   * @param \Drupal\helfi_atv\AtvDocument $atv_document
   *   Atv document object.
   * @param string $submission_id
   *   Calculated submission id.
   */
  public function __construct(
    WebformSubmissionInterface $webform_submission,
    AtvDocument $atv_document,
    string $submission_id
  ) {
    $this->submission = $webform_submission;
    $this->atvDocument = $atv_document;
    $this->submissionId = $submission_id;
  }

  /**
   * Get the Atv document.
   *
   * @return \Drupal\helfi_atv\AtvDocument
   *   A Drupal entity.
   */
  public function getAtvDocument() {
    return $this->atvDocument;
  }

  /**
   * Get the webform submission.
   *
   * @return \Drupal\webform\WebformSubmissionInterface
   *   A Webform submission.
   */
  public function getSubmission() {
    return $this->submission;
  }

  /**
   * Get the calculated submission id.
   *
   * @return string
   *   Submission id.
   */
  public function getSubmissionId() {
    return $this->submissionId;
  }

}
