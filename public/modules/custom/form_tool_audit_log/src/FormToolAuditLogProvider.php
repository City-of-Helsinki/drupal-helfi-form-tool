<?php

namespace Drupal\form_tool_audit_log;

use Drupal\helfi_audit_log\AuditLogOperation;
use Drupal\helfi_audit_log\HelfiAuditLogProviderBase;
use Drupal\webform_formtool_handler\Plugin\WebformHandler\FormToolWebformHandler;

/**
 * Form Tool Audit Log Logger.
 */
class FormToolAuditLogProvider extends HelfiAuditLogProviderBase {

  public $message = [];

  /**
   *
   */
  public function logData(array $message) : AuditLogOperation {

    $this->message = $message;

    // Populate some context info from environment.
    $this->message["context"] = [
      "environment" => FormToolWebformHandler::getAppEnv(),
      "app_version" => "1.0",
    ];

    return new AuditLogOperation($this);

  }

  public function getLogStructure() : array {
    return [
      "context",
      "operation",
      "status",
      "target"
    ];
  }

}
