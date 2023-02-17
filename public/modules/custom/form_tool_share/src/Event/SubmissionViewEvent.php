<?php

namespace Drupal\form_tool_share\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event submission view.
 */
class SubmissionViewEvent extends Event {

  const SUBMISSION_VIEW = 'form_tool_share.submission.view';

  /**
   * Submission row.
   *
   * @var object
   */
  private $submission;

  /**
   * Current request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * Construct a new event.
   *
   * @param object $submission
   *   Submission database row.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   */
  public function __construct(object $submission, Request $request) {
    $this->submission = $submission;
    $this->request = $request;
  }

  /**
   * Get the entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A Drupal entity.
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Get the submission.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A Drupal entity.
   */
  public function getSubmission() {
    return $this->submission;
  }

}
