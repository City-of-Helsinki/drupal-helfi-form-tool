<?php

namespace Drupal\form_tool_share\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Form Tool Share routes.
 */
class FormToolShareController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    return new JsonResponse(['data' => $this->getData(), 'method' => 'GET', 'status' => 200]);
  }

  /**
   *
   */
  public function getData(): array {
    $currentUser = \Drupal::currentUser();
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($currentUser->id());
    return [
      'user' => $user->toArray(),
      'htsd' => 'asdf',
      'fdsa' => 'asdf',
      'hdgh' => 'asdf',
      'sdfg' => 'asdf',
      'gfsd' => 'asdf',
    ];
  }

}
