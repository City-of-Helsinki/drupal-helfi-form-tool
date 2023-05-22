<?php

namespace Drupal\autologout_extend\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('autologout.ajax_logout')) {
      $route->setDefault(
        '_controller',
        '\Drupal\autologout_extend\Controller\LogoutController::ajaxLogout'
      );
    }
  }

}
