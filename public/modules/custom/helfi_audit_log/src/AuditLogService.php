<?php

namespace Drupal\helfi_audit_log;

use Drupal;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Exception;

/**
 * AuditLog service.
 */
class AuditLogService {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * Constructs a HelfiAuditLog object.
   */
  public function __construct() {
    $this->connection = Drupal::database();
  }

  /**
   * Operation that logs the message to database.
   *
   * @param array $message
   *   Message that is merged with generic data and logged to database.
   */
  public function logOperation(array $message) {

    $current_timestamp = Drupal::time()->getCurrentMicroTime();

    $user = Drupal::currentUser();

    // Determine user role based on if user has admin role.
    $role = in_array("admin", $user->getRoles()) ? "ADMIN" : "USER";

    $operation_data = [
      "origin" => "AVUSTUSASIOINTI-DRUPAL",
      "source" => "DRUPAL",
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
        "ip_address" => Drupal::request()->getClientIp(),
      ],
    ];

    // Merge message and generic operation data.
    $operation_data = array_merge($operation_data, $message);

    try {
      $result = $this->connection->insert('helfi_audit_logs')
        ->fields([
          'created_at' => Drupal::time()->getRequestTime(),
          'is_sent' => 0,
          'message' => Json::encode(['audit_event' => $operation_data]),
        ])
        ->execute();
    }
    catch (Exception $e) {
    }

  }

}
