<?php

namespace Drupal\openid_logout_redirect\Service;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectService to handle saving and retriving logout redirection.
 */
class RedirectService {

  const COOKIE_NAME = 'service_logout_redirect';
  const DEFAULT_URL = 'https://hel.fi/';

  /**
   * The language manager object.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The request stack.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * RedirectService constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   Language manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler.
   */
  public function __construct(
    RequestStack $request_stack,
    LanguageManagerInterface $language_manager,
    ModuleHandlerInterface $module_handler,
    ) {
    $this->requestStack = $request_stack;
    $this->languageManager = $language_manager;
    $this->moduleHandler = $module_handler;
  }

  /**
   * Sets logout url to cookie.
   *
   * @param \Symfony\Component\HttpFoundation\Response $response
   *   Response.
   */
  public function setLogoutRedirectUrl(Response $response) {

    $dest = $this->requestStack->getCurrentRequest()->get('dest');

    $this->moduleHandler->invokeAll('openid_logout_redirect_alter_url', [&$dest]);

    if (!$dest && !UrlHelper::isValid($dest)) {
      $dest = $this->getDefaultUrl();
    }

    $lifetime = strtotime("+15 minutes");
    $cookie = new Cookie(self::COOKIE_NAME, $dest, $lifetime);
    $response->headers->setCookie($cookie);

  }

  /**
   * Gets logout url from cookie.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse
   *   Redirect response.
   */
  public function getLogoutRedirectUrl() {
    $url = $this->requestStack->getCurrentRequest()->cookies->get(self::COOKIE_NAME);

    if (empty($url)) {
      $url = $this->getDefaultUrl();
    }

    $response = new TrustedRedirectResponse($url);
    $response->headers->clearCookie(self::COOKIE_NAME);
    return $response;
  }

  /**
   * Returns default url with a current language.
   *
   * @return string
   *   Default url with language selection
   */
  private function getDefaultUrl(): string {
    return self::DEFAULT_URL . $this->languageManager->getCurrentLanguage()->getId();
  }

}
