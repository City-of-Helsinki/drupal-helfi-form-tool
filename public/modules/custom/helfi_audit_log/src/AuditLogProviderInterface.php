<?php

namespace Drupal\helfi_audit_log;

interface AuditLogProviderInterface {


  public function getLogStructure();

  public function logData(array $logData);


}
