<?php

namespace Drupal\form_tool_audit_log;

use Drupal;
use Drupal\helfi_audit_log\Event\AuditLogEvent;
use Drupal\webform_formtool_handler\Plugin\WebformHandler\FormToolWebformHandler;

/**
 * Form Tool Audit Log Logger.
 */
class FormToolAuditLog {

  /**
   *
   */
  public static function log(array $message) {

    // Populate some context info from environment.
    $message["context"] = [
      "environment" => FormToolWebformHandler::getAppEnv(),
    // @todo .
      "app_version" => "1.0",
    ];

    // Instantiate logging event.
    $event = new AuditLogEvent($message);

    // Get the event_dispatcher service and dispatch the event.
    $event_dispatcher = Drupal::service('event_dispatcher');
    $event_dispatcher->dispatch($event, AuditLogEvent::EVENT_NAME);
  }

}
