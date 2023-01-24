<?php

namespace Drupal\form_tool_profile_data\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Http\RequestStack;
use Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData;
use Drupal\helfi_helsinki_profiili\TokenExpiredException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Refresh Helsinki Profile data from backend and redirect user back.
 */
class RefreshProfileDataController extends ControllerBase {

  /**
   * The helfi_helsinki_profiili.userdata service.
   *
   * @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData
   */
  protected HelsinkiProfiiliUserData $helfiHelsinkiProfiiliUserdata;

  /**
   * Request stack for session access.
   *
   * @var \Drupal\Core\Http\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * The controller constructor.
   *
   * @param \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $helfi_helsinki_profiili_userdata
   *   The helfi_helsinki_profiili.userdata service.
   * @param \Drupal\Core\Http\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(
    HelsinkiProfiiliUserData $helfi_helsinki_profiili_userdata,
    RequestStack $requestStack,
  ) {
    $this->helfiHelsinkiProfiiliUserdata = $helfi_helsinki_profiili_userdata;
    $this->requestStack = $requestStack;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('helfi_helsinki_profiili.userdata'),
      $container->get('request_stack'),
    );
  }

  /**
   * Refresh profile data from HP and redirect user back.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirect back to previous page.
   */
  public function build(): RedirectResponse {

    try {
      $newUserData = $this->helfiHelsinkiProfiiliUserdata->getUserProfileData(TRUE);
      $this->messenger()->addStatus('Profile data updated');
    }
    catch (TokenExpiredException $e) {
      $this->messenger()->addError($e->getMessage());
    }

    $referer = $this->requestStack->getCurrentRequest()->server->get('HTTP_REFERER');

    return new RedirectResponse($referer, 302);
  }

}
