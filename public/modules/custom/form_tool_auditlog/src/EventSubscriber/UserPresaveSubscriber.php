<?php

namespace Drupal\form_tool_auditlog\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\form_tool_auditlog\Event\UserPresaveEvent;
use Drupal\helfi_audit_log\AuditLogServiceInterface;
use Drupal\user\Entity\User;

/**
 * Monitors all user save events and logs any role/status changes to watchdog.
 */
class UserPresaveSubscriber implements EventSubscriberInterface {

  /**
   * The audit log service.
   *
   * @var \Drupal\helfi_audit_log\AuditLogServiceInterface
   */
  protected $logger;

  /**
   * Constructs a logger object.
   *
   * @param \Drupal\helfi_audit_log\AuditLogServiceInterface $logger
   *   A LoggerInterface object.
   */
  public function __construct(AuditLogServiceInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[UserPresaveEvent::USER_PRESAVE][] = ['onUserPresave'];
    return $events;
  }

  /**
   * Get the user being saved and see if there are role changes to log.
   *
   * @param \Drupal\role_log\Event\UserPresaveEvent $event
   *   A UserPresaveEvent event.
   */
  public function onUserPresave(UserPresaveEvent $event) {
    $user = $event->getUser();
    $this->logRoleChanges($user);
  }

  /**
   * Log role changes.
   *
   * @param \Drupal\user\Entity\User $user
   *   A user entity.
   */
  protected function logRoleChanges(User $user) {

    if (!$user->isNew()) {
      $original = $user->original;
      $old_roles = $original->getRoles();
      $new_roles = $user->getRoles();

      // Only log saves of existing users if there are role changes.
      if ($old_roles != $new_roles) {
        $message = [
          'operation' => 'UPDATE',
          'target' => [
            'id' => $user->id(),
            'type' => 'USER',
            'name' => 'User role update',
            'old_roles' => $old_roles,
            'new_roles' => $new_roles,
            'removed_roles' => array_diff($old_roles, $new_roles),
            'added_roles' => array_diff($new_roles, $old_roles),
          ],
          'actor' => [
            'id' => \Drupal::currentUser()->id(),
          ],
        ];

        $this->logger->dispatchEvent($message);
      }
    }
  }

}
