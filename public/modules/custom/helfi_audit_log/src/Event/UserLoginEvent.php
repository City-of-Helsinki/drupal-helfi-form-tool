<?php

namespace Drupal\helfi_audit_log\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountInterface;

/**
 * Event that is fired when a user logs in.
 */
class UserLoginEvent extends Event {

  const EVENT_NAME = 'helfi_audit_user_login';

  /**
   * The user account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  public $account;

  /**
   * Constructs the object.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account of the user logged in.
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

}
