<?php

namespace Drupal\helfi_gdpr_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for helfi_gdpr_api routes.
 */
class TestaaTkApiController extends ControllerBase {

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
  public function __construct(
    RequestStack $request_stack,
  ) {
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack'),
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    $foo = $this->requestStack->getCurrentRequest()->get('data');

    $response = new Response();
    $response->setContent($foo);
    $response->headers->set('Content-Type', 'text/xml');

    return $response;

  }

}
