<?php

namespace Drupal\form_tool_profile\EventSubscriber;

use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event subscriber class to check access to content for helsinki profile users.
 *
 * @package Drupal\form_tool_profile\EventSubscriber
 */
class RequestEventSubscriber implements EventSubscriberInterface {

  const BLOCKED_ROUTES = [
    'entity.user.canonical',
  ];

  /**
   * The account proxy.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $account;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $route;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs the event subscriber.
   *
   * @param \Drupal\Core\Session\AccountProxy $account
   *   Account proxy.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   Current route match.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack.
   */
  public function __construct(AccountProxy $account, CurrentRouteMatch $route, RequestStack $request_stack) {
    $this->account = $account;
    $this->route = $route;
    $this->requestStack = $request_stack;
  }

  /**
   * KernelEvent::Request callback.
   */
  public function checkAccess(RequestEvent $event) {

    if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
      return;
    }

    if ($this->account->isAnonymous()) {
      return;
    }

    if (!$this->userHasHelsinkiProfile($this->account)) {
      return;
    }

    $node = $this->requestStack->getCurrentRequest()->attributes->get('node');
    $route_name = $this->route->getRouteName();

    if ($this->isBlockedRouteName($route_name)) {
      $path = Url::fromRoute('system.403')->toString();
      $response = new RedirectResponse($path);
      return $response->send();
    }

    if ($node && $node->getType() !== 'webform') {
      throw new AccessDeniedHttpException();
    }

  }

  /**
   * Check if route should be blocked from helsinki profile users.
   *
   * @param string $routeName
   *   Drupal route name.
   *
   * @return bool
   *   Boolean value telling if route should be blocked.
   */
  private function isBlockedRouteName(string $routeName) {
    return in_array($routeName, self::BLOCKED_ROUTES);
  }

  /**
   * Checks if account has Helsinki profile (strong or weak).
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   Account from the request.
   *
   * @return bool
   *   Boolean value to tell if account has helsinki profile
   */
  private function userHasHelsinkiProfile(AccountProxyInterface $account) {
    $roles = $account->getRoles();
    $intersectedRoles = array_intersect(
      $roles, ['helsinkiprofiili_vahva', 'helsinkiprofiili_heikko']
    );

    return count($intersectedRoles) > 0;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkAccess'];
    return $events;
  }

}
