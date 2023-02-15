<?php

namespace Drupal\form_tool_auditlog\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Event for user presave.
 */
class UserPresaveEvent extends Event {

  const USER_PRESAVE = 'form_tool_auditlog.user.presave';

  /**
   * User entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  private $entity;

  /**
   * Construct a new event.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   A Drupal entity.
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A Drupal entity.
   */
  public function getUser() {

    return $this->entity;
  }

}
