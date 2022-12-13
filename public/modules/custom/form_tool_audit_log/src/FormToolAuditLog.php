<?php

namespace Drupal\form_tool_audit_log;

class FormToolAuditLog {

  public static function log(array $message) {

    $provider = new FormToolAuditLogProvider();

    $provider->logData($message);

  }

}
