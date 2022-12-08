<?php

namespace Drupal\helfi_audit_log;

/**
 *
 */
class HelfiAuditLogProvider extends HelfiAuditLogProviderBase {

  /**
   *
   */
  public function getLogStructure(): array {
    return [
      'origin',
      'operation',
      'status',
      'date_time_epoch',
      'date_time',
      'actor' => [
        'role', 'user_id', 'ip_address',
      ],
      'target' => [
        'id', 'type', 'name', 'diff',
      ],
    ];
  }

  /**
   *
   */
  public function logData(array $logData) {

    return $this->getLogStructure();
  }

}
