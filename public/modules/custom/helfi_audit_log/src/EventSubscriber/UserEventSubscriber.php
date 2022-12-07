<?php

namespace Drupal\helfi_audit_log\EventSubscriber;

use Drupal\helfi_audit_log\AuditLogService;
use Drupal\helfi_audit_log\Event\UserLoginEvent;
use Drupal\helfi_audit_log\Event\UserLogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Helfi audit log user event subscriber.
 */
class UserEventSubscriber implements EventSubscriberInterface {

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
      UserLoginEvent::EVENT_NAME => 'userLogin',
      UserLogoutEvent::EVENT_NAME => 'userLogout',
    ];
  }

  /**
   * React to user login.
   *
   * @param \Drupal\helfi_audit_log\Event\UserLoginEvent $event
   *   User login event.
   */
  public function userLogin(UserLoginEvent $event) {

    $this->log_service->logOperation("DRUPAL_LOGIN",
      "SUCCESS",
      $event->account->id(),
      "USER",
      "User login",
      "",
      $event->account
    );

  }

  /**
   * React to user logout.
   *
   * @param \Drupal\helfi_audit_log\Event\UserLogoutEvent $event
   *   User login event.
   */
  public function userLogout(UserLogoutEvent $event) {

    $this->log_service->logOperation("DRUPAL_LOGOUT",
      "SUCCESS",
      $event->account->id(),
      "USER",
      "User logout",
      "",
      $event->account
    );

  }

}
