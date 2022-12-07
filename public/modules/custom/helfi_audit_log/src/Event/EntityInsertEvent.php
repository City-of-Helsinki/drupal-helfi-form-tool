<?php

namespace Drupal\helfi_audit_log\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Event that is fired when any entity is inserted.
 */
class EntityInsertEvent extends Event {

  const EVENT_NAME = 'helfi_audit_entity_insert';

  /**
   * The entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  public $entity;

  /**
   * Constructs the object.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity that is being inserted.
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

}
