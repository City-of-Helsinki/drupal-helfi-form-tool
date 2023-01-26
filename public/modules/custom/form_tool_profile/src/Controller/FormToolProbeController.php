<?php

namespace Drupal\form_tool_profile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Form tool profile routes.
 */
class FormToolProbeController extends ControllerBase {

  /**
   * Response to readiness probe.
   */
  public function readiness(): JsonResponse {
    return new JsonResponse(['data' => [], 'method' => 'GET', 'status' => 200]);
  }

  /**
   * Response to healthz probe.
   */
  public function healthz(): JsonResponse {
    return new JsonResponse(['data' => [], 'method' => 'GET', 'status' => 200]);
  }

}
