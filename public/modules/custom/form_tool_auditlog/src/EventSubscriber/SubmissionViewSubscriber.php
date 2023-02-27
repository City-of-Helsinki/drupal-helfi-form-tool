<?php

namespace Drupal\form_tool_auditlog\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\form_tool_share\Event\SubmissionViewEvent;
use Drupal\helfi_audit_log\AuditLogServiceInterface;

/**
 * Monitors submission view events and logs them to audit log.
 */
class SubmissionViewSubscriber implements EventSubscriberInterface {

  /**
   * The audit log service.
   *
   * @var \Drupal\helfi_audit_log\AuditLogServiceInterface
   */
  protected $logger;

  /**
   * Constructs a logger object.
   *
   * @param \Drupal\helfi_audit_log\AuditLogServiceInterface $logger
   *   A LoggerInterface object.
   */
  public function __construct(AuditLogServiceInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[SubmissionViewEvent::SUBMISSION_VIEW][] = ['onSubmissionView'];
    return $events;
  }

  /**
   * Mark submission view to audit log.
   *
   * @param \Drupal\form_tool_share\Event\SubmissionViewEvent $event
   *   A SubmissionViewEvent event.
   */
  public function onSubmissionView(SubmissionViewEvent $event) {

    $request = $event->getRequest();
    $submissionData = $event->getSubmission();
    $requestUserData = $request->getSession()->get('userData');
    $user_uuid = $requestUserData['sub'] ?? NULL;

    $message = [
      'operation' => 'READ',
      'status'    => 'SUCCESS',
      'target' => [
        'id' => $submissionData->submission_uuid,
        'type' => 'SUBMISSION',
      ],
    ];

    if ($user_uuid === $submissionData->user_uuid) {
      $message['actor'] = [
        'submitter' => TRUE,
        'user_id' => $user_uuid,
      ];
    }
    else {
      $message['actor'] = [
        'controller' => TRUE,
        'id' => \Drupal::currentUser()->id(),
      ];
    }

    $this->logger->dispatchEvent($message);

  }

}
