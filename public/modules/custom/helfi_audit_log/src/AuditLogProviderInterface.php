<?php

namespace Drupal\helfi_audit_log;

/**
 * Interface for Audit Log Provider.
 */
interface AuditLogProviderInterface {

  /**
   * Structure for log data.
   */
  public function getLogStructure();

  /**
   * Logging data.
   */
  public function logData(array $logData);

}
