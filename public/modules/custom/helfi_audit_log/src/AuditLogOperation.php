<?php

namespace Drupal\helfi_audit_log;

use Drupal;

class AuditLogOperation {

  public function __construct(HelfiAuditLogProviderBase $provider) {

    // Basic validation for message

    $structure_keys = $provider->getLogStructure();
    sort($structure_keys);

    $message_keys = array_keys($provider->message);
    sort($message_keys);

    if($structure_keys != $message_keys) {

      throw new AuditLogException("Message has incorrect structure");

    } else {

      // Message ok, call service

      Drupal::service("helfi_audit_log.audit_log")->logOperation($provider->message);

    }

  }

}
