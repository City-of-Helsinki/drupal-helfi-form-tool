<?php

namespace Drupal\helfi_audit_log\EventSubscriber;

use Drupal\helfi_audit_log\AuditLogService;
use Drupal\helfi_audit_log\Event\EntityInsertEvent;
use Drupal\helfi_audit_log\Event\WebformViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Helfi audit log entity event subscriber.
 */
class EntityEventSubscriber implements EventSubscriberInterface {

  /**
   * @var AuditLogService
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
      WebformViewEvent::EVENT_NAME => 'webformViewed',
      EntityInsertEvent::EVENT_NAME => 'entityInserted',
    ];
  }

  /**
   * React to webform being viewed.
   *
   * @param \Drupal\helfi_audit_log\Event\WebformViewEvent $event
   *   User login event.
   */
  public function webformViewed(WebformViewEvent $event) {

    $this->log_service->logOperation("READ",
      "SUCCESS",
      $event->entity->id(),
      "WEBFORM",
      "Form viewed",
      ""
    );

  }

  /**
   * React to any entity being inserted.
   *
   * @param \Drupal\helfi_audit_log\Event\EntityInsertEvent $event
   *   User login event.
   */
  public function entityInserted(EntityInsertEvent $event) {

    $this->log_service->logOperation("CREATE",
      "SUCCESS",
      $event->entity->id(),
      mb_strtoupper($event->entity->bundle()),
      "Entity inserted",
      ""
    );

  }

}
