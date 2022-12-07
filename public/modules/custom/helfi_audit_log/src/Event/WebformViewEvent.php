<?php

namespace Drupal\helfi_audit_log\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Event that is fired when a webform node is viewed.
 */
class WebformViewEvent extends Event {

  const EVENT_NAME = 'helfi_audit_webform_view';

  /**
   * The webform entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  public $entity;

  /**
   * Constructs the object.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The webform entity that is being viewed.
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

}
