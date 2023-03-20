<?php

namespace Drupal\autologout_extend\EventSubscriber;

use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event subscriber class to check autologout session refresh.
 *
 * @package Drupal\autologout_extend\EventSubscriber
 */
class RequestEventSubscriber implements EventSubscriberInterface {

  /**
   * Constructs the event subscriber.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $routeMatch
   *   Current route match.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   Request stack.
   * @param \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $userData
   *   Helsinki profiili user data service.
   */
  public function __construct(
    private CurrentRouteMatch $routeMatch,
    private RequestStack $requestStack,
    private HelsinkiProfiiliUserData $userData) {
  }

  /**
   * KernelEvent::Request callback.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   Request event.
   */
  public function checkRefresh(RequestEvent $event) {
    $routeName = $this->routeMatch->getRouteName();
    if ($routeName === 'autologout.ajax_set_last') {
      $refresh = $this->requestStack->getCurrentRequest()->get('refresh');
      if ($refresh == 'true') {
        try {
          $refreshed = $this->userData->refreshTokens();
          $currentRequest = $this->requestStack->getCurrentRequest();
          $userData = $currentRequest->getSession()->get('userData');
          if ($refreshed) {
            $message = [
              'operation' => 'OPENID_REFRESH',
              'status'    => 'SUCCESS',
              'target' => [
                'id' => $userData['sub'],
              ],
            ];
            $auditLog = \Drupal::service('helfi_audit_log.audit_log');
            if ($auditLog) {
              $auditLog->dispatchEvent($message);
            }
          }
        }
        catch (\Exception $e) {

        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkRefresh'];
    return $events;
  }

}
