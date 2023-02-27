<?php

namespace Drupal\form_tool_auditlog\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\helfi_audit_log\Event\AuditLogEvent;

/**
 * Monitors submission view events and logs them to audit log.
 */
class AuditLogEventSubscriber implements EventSubscriberInterface {

  const ORIGIN = 'FORM-TOOL-DRUPAL';

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[AuditLogEvent::LOG][] = ['onLog'];
    return $events;
  }

  /**
   * Modify audit log events.
   *
   * @param \Drupal\helfi_audit_log\Event\AuditLogEvent $event
   *   An AuditLogEvent event.
   */
  public function onLog(AuditLogEvent $event) {
    $event->setOrigin(self::ORIGIN);
  }

}
