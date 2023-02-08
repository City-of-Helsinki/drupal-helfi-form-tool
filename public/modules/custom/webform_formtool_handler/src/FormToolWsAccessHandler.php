<?php

namespace Drupal\webform_formtool_handler;

use Drupal\webform\Access\WebformAccessResult;
use Drupal\webform\WebformSubmissionAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Override access control from.
 */
class FormToolWsAccessHandler extends WebformSubmissionAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  public function checkAccess(WebformSubmissionInterface|EntityInterface $entity, $operation, AccountInterface $account) {

    $webform = $entity->getWebform();
    $webformOwner = $webform->getOwner();
    $webformOwnerRoles = $webformOwner->getRoles();
    $thirdPartySettings = $webform->getThirdPartySettings('form_tool_webform_parameters');
    $userRoles = $account->getRoles();

    // No access to anonymous to any webform submissions.
    if ($account->isAnonymous()) {
      return WebformAccessResult::forbidden();
    }

    // Admins DO NOT have access to any submissions.
    // To allow user access they MUST NOT have these roles.
    if (in_array(['admin', 'verkkolomake_admin'], $userRoles)) {
      return WebformAccessResult::forbidden();
    }

    // Webform owner has access to submission when in WIP state.
    if (($thirdPartySettings["status"] == 'wip') && $webformOwner->id() == $account->id()) {
      return WebformAccessResult::allowed();
    }

    // Load saved data for this submission.
    $result = \Drupal::service('database')
      ->query("SELECT document_uuid,admin_owner,admin_roles,user_uuid FROM {form_tool_map} WHERE submission_uuid = :submission_uuid", [
        ':submission_uuid' => $entity->get('uuid')->value,
      ]);
    $data = $result->fetchObject();

    $adminRoles = explode(',', $data->admin_roles);

    foreach ($adminRoles as $rid) {
      // If user has a role to access this webform submission.
      if (in_array($rid, $userRoles)) {
        return WebformAccessResult::allowed();
      }
    }

    // Admin owner has access if state is WIP.
    if (($thirdPartySettings["status"] == 'wip') && $data->admin_owner == $account->getEmail()) {
      return WebformAccessResult::allowed();
    }

    // if user does not have either helsinki profile role, they do not have access
    // and if they do, they must be the submitter below.
    if (!self::inArrayWildcard($userRoles, 'helsinkiprofiili')) {
      return WebformAccessResult::forbidden();
    }

    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $helProfiiliData */
    $helProfiiliData = \Drupal::service('helfi_helsinki_profiili.userdata');
    $userData = $helProfiiliData->getUserData();

    if (!$userData) {
      return WebformAccessResult::forbidden();
    }

    // User can access their own submission.
    if ($data->user_uuid == $userData["sub"]) {
      return WebformAccessResult::allowed();
    }

    return WebformAccessResult::forbidden();
  }

  /**
   * Check if wildcard value is in array.
   *
   * @param array $haystack
   *   Array we're looking for values.
   * @param string $needle
   *   Value we want to see if it's in above array.
   *
   * @return bool
   *   True if array is found and false if not.
   */
  public static function inArrayWildcard(array $haystack, string $needle): bool {
    $matches = array_filter($haystack, function ($var) use ($needle) {
      return (bool) preg_match("/$needle/i", $var);
    });

    return !empty($matches);

  }

}
