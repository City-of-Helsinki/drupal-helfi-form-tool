<?php

namespace Drupal\form_tool_auditlog\EventSubscriber;

use Drupal\helfi_audit_log\AuditLogServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\webform_formtool_handler\Event\WebformSubmissionEvent;

/**
 * Monitors submission view events and logs them to audit log.
 */
class WebformSubmissionSubscriber implements EventSubscriberInterface {

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
    $events[WebformSubmissionEvent::SUBMISSION_EVENT][] = ['onSubmissionCreate'];
    return $events;
  }

  /**
   * Mark submission to audit log.
   *
   * @param \Drupal\webform_formtool_handler\Event\WebformSubmissionEvent $event
   *   A WebformSubmissionEvent event.
   */
  public function onSubmissionCreate(WebformSubmissionEvent $event) {

    $webform_submission = $event->getSubmission();
    $atvDocument = $event->getAtvDocument();
    $formToolSubmissionId = $event->getSubmissionId();

    $message = [
      'operation' => 'CREATE',
      'status'    => 'SUCCESS',
      'target' => [
        'user_id' => $webform_submission->uuid(),
        'type' => 'SUBMISSION',
        'name' => 'Webform submission',
        'document_uuid' => $atvDocument->getId(),
        'user_uuid' => $atvDocument->getUserId(),
        'form_tool_id' => $formToolSubmissionId,
      ],
      'actor' => [
        'user_id' => $atvDocument->getUserId(),
      ],
    ];

    $this->logger->dispatchEvent($message);
  }

}
