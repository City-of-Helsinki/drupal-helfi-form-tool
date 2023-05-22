<?php

namespace Drupal\autologout_extend\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for autologout module routes.
 */
class LogoutController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('autologout.manager'),
      $container->get('datetime.time')
    );
  }

  /**
   * Alternative logout.
   */
  public function altLogout() {
    $url = Url::fromRoute('user.logout');
    return new RedirectResponse($url->toString());
  }

  /**
   * AJAX logout.
   */
  public function ajaxLogout() {
    $response = new AjaxResponse();
    $response->setStatusCode(200);
    return $response;
  }

}
