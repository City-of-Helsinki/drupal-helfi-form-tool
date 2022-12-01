<?php

namespace Drupal\helfi_audit_log\EventSubscriber;

use Drupal\helfi_audit_log\AuditLogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Helfi Drupal AuditLog event subscriber.
 */
class AuditLogExceptionSubscriber implements EventSubscriberInterface {

  /**
   * Save audit log to database.
   *
   * @var AuditLogService
   */
  protected AuditLogService $auditLogService;

  /**
   * Kernel response event handler.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   Response event.
   */
  public function onException(FilterResponseEvent $event) {
    $this->messenger->addStatus(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::EXCEPTION][] = [
      'onException',
      -256,
    ];
    return $events;
  }

}
