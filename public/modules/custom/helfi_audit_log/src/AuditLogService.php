<?php

namespace Drupal\helfi_audit_log;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;

/**
 * AuditLog service.
 */
class AuditLogService {

  protected Connection $connection;

  /**
   * Constructs a HelfiAuditLog object.
   */
  public function __construct() {
    $this->connection = \Drupal::database();
  }

  /**
   *
   */
  public function logOperation(string $operation, string $status, int $target_id, string $target_type, string $target_name, string $target_diff, AccountInterface $user = NULL) {

    $current_timestamp = \Drupal::time()->getCurrentMicroTime();

    if (!$user) {
      // If user account is not specified, use current user.
      $user = \Drupal::currentUser();
    }

    // Determine user role based on if user has admin role.
    $role = in_array("admin", $user->getRoles()) ? "ADMIN" : "USER";

    $operation_data = [
      "origin" => "",
      "operation" => $operation,
      "status" => $status,
      "date_time" => floor($current_timestamp * 1000),
      // Format should be yyyy-MM-ddThh:mm:ss.SSSZ.
      "date_time_epoch" =>
      date("Y-m-d\TH:i:s", floor($current_timestamp)) .
      "." .
      str_pad(floor(($current_timestamp - floor($current_timestamp)) * 1000), 3, "0", STR_PAD_LEFT) .
      "Z",
      "actor" => [
        "role" => $role,
        "user_id" => $user->id(),
        "ip_address" => \Drupal::request()->getClientIp(),
      ],
      "target" => [
        "id" => $target_id,
        "type" => $target_type,
        "name" => $target_name,
        "diff" => $target_diff,
      ],
    ];

    try {
      $result = $this->connection->insert('helfi_audit_logs')
        ->fields([
          'created_at' => \Drupal::time()->getRequestTime(),
          'is_sent' => 0,
          'message' => Json::encode(['audit_event' => $operation_data]),
        ])
        ->execute();
    }
    catch (\Exception $e) {
    }

  }

}
