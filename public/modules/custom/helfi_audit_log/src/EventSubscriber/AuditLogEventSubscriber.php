<?php

namespace Drupal\helfi_audit_log\EventSubscriber;

use Drupal\helfi_audit_log\AuditLogService;
use Drupal\helfi_audit_log\Event\AuditLogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Helfi audit log event subscriber.
 */
class AuditLogEventSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\helfi_audit_log\AuditLogService
   * Helfi audit log service
   */
  private $log_service;

  /**
   *
   */
  public function __construct() {
    $this->log_service = new AuditLogService();
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      AuditLogEvent::EVENT_NAME => 'logMessage',
    ];
  }

  /**
   * @param array $message
   *
   * @return void
   */
  public function logMessage(AuditLogEvent $event) {

    $this->log_service->logOperation($event->message);

  }

}
