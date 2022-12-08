<?php

namespace Drupal\helfi_audit_log\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Event that is fired when a new audit log needs to be logged.
 */
class AuditLogEvent extends Event {

  const EVENT_NAME = 'helfi_audit_log';

  /**
   * The log message.
   *
   * @var array
   */
  public $message;

  /**
   * Constructs the object.
   *
   * @param array $message
   *   The message that needs to be logged.
   */
  public function __construct(array $message) {
    $this->message = $message;
  }

}
