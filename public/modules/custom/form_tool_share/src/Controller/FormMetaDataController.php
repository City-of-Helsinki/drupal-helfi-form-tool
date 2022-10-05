<?php

namespace Drupal\form_tool_share\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\Webform;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Form Tool Share routes.
 */
class FormMetaDataController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(): JsonResponse {

    $retval = [];

    $webforms = Webform::loadMultiple();

    foreach ($webforms as $key => $webform) {
      $handlers = $webform->getHandlers();
      $status = $webform->get('status');
      if ($handlers->has('formtool_webform_handler')) {
        $tps = $webform->getThirdPartySettings('form_tool_webform_parameters');
        if ($status == 'open' && (isset($tps['status']) && $tps['status'] === 'public')) {
          $retval[] = [
            'id' => $webform->id(),
            'title' => $webform->get('title'),
          ] + $tps;
        }

      }
    }

    return new JsonResponse([
      'data' => $retval,
      'method' => 'GET',
      'status' => 200,
    ]);

  }

}
