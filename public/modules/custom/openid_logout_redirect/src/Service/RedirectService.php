<?php

namespace Drupal\openid_logout_redirect\Service;

use Drupal\Core\Http\RequestStack;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectService to handle saving and retriving logout redirection.
 */
class RedirectService {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * RedirectService constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack.
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
  }

  /**
   * Sets logout url to cookie.
   *
   * @param string $url
   *   Url.
   * @param \Symfony\Component\HttpFoundation\Response $response
   *   Response.
   */
  public function setLogoutRedirectUrl(string $url, Response $response) {

    if (empty($url)) {
      return;
    }

    $cookie = new Cookie('logout_redirect_url', $url);
    $response->headers->setCookie($cookie);

  }

  /**
   * Gets logout url from cookie.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   Redirect response.
   */
  public function getLogoutRedirectUrl() {
    $url = $this->requestStack->getCurrentRequest()->cookies->get('logout_redirect_url');

    if (empty($url)) {
      $url = 'https://hel.fi/fi';
    }

    $response = new TrustedRedirectResponse($url);
    $response->headers->clearCookie('logout_redirect_url');
    return $response;
  }

}
