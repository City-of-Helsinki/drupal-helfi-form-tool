<?php

namespace Drupal\helfi_audit_log;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;

/**
 * AuditLog service.
 */
class AuditLogService {

  protected Connection $connection;

  protected AuditLogProviderInterface $auditLogProvider;

  /**
   * Constructs a HelfiAuditLog object.
   *
   * @param Connection $database
   *  DB connection
   */
  public function __construct(Connection $database) {
    $this->connection = $database;
  }

  public function setProvider(AuditLogProviderInterface $auditLogProvider) {
    $this->auditLogProvider = $auditLogProvider;
  }


  public function log(array $logData) {

    $log = $this->auditLogProvider->logData($logData);

    $date_time_epoch = \Drupal::time()->getRequestTime();
    $dd = date('c', $date_time_epoch);
    $dt = new \DateTime($dd);
    $dt->setTimezone(new \DateTimeZone('Europe/Helsinki'));

    try {
      $result = $this->connection->insert('helfi_audit_logs')
        ->fields([
          'created_at' => \Drupal::time()->getRequestTime(),
          'is_sent' => 0,
          'message' => Json::encode(['audit_event' => $log])
        ])
        ->execute();
      $d = 'asdf';
    } catch (\Exception $e) {
      $d = 'asdf';
    }

  }


}
