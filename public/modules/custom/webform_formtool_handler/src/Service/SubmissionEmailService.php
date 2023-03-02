<?php

namespace Drupal\webform_formtool_handler\Service;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;

/**
 * EmailService to handle submission confirmation mails.
 */
class SubmissionEmailService {

  const MODULE_NAME = 'webform_formtool_handler';

  /**
   * RedirectService constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   Language manager.
   * @param \Drupal\Core\Mail\MailManagerInterface $mailManager
   *   Mail manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   Renderer instance.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger instance.
   */
  public function __construct(
    protected LanguageManagerInterface $languageManager,
    protected MailManagerInterface $mailManager,
    protected RendererInterface $renderer,
    protected MessengerInterface $messenger,
  ) {
  }

  /**
   * Sends a confirmation to the form submitter.
   *
   * @param string $to
   *   Email address to send the email.
   * @param string $submission_id
   *   Submission id.
   */
  public function sendSubmitterEmail(string $to, string $submission_id) {
    $key = 'submission_email_notify_submitter';
    $langCode = $this->languageManager->getCurrentLanguage()->getId();
    $url = $this->generateSubmissionLink($submission_id);

    $params['message'] = $this->renderEmailBody('submission_email_notify_submitter', $url->toString(), $langCode);

    $send = TRUE;

    $result = $this->mailManager->mail(self::MODULE_NAME, $key, $to, $langCode, $params,
      NULL, $send);

    $success = $result['result'] === TRUE;
    if (!$success) {
      $this->messenger->addError(t('There was a problem sending
      confirmation message and it was not sent.'), 'error');
    }
    else {
      $this->messenger->addStatus(t('Your message has been sent.'));
    }

    return $success;
  }

  /**
   * Sends a confirmation to a controller person.
   *
   * @param string $to
   *   Email address to send the email.
   * @param string $submission_id
   *   Submission id.
   * @param string $title
   *   Title of the form.
   */
  public function sendControllerEmail(string $to, string $submission_id, string $title) {
    $key = 'submission_email_notify';
    // For now all controller mails are in finnish.
    $langCode = 'fi';
    $url = $this->generateSubmissionLink($submission_id);

    $params['message'] = $this->renderEmailBody('submission_email_notify', $url->toString(), $langCode, $title);
    $params['form_title'] = $title;

    $send = TRUE;

    $result = $this->mailManager->mail(self::MODULE_NAME, $key, $to, $langCode, $params,
      NULL, $send);

    $success = $result['result'] === TRUE;
    if (!$success) {
      $this->messenger->addError(t('There was a problem sending
      confirmation message and it was not sent.'), 'error');
    }
    else {
      $this->messenger->addStatus(t('Your message has been sent.'));
    }

    return $success;
  }

  /**
   * Renders the email body.
   *
   * @param string $themeKey
   *   Theme key to render email.
   * @param string $url
   *   Submission url.
   * @param string $langCode
   *   Language of the email.
   * @param string $formTitle
   *   Title of the form. Used in controller subject and body.
   */
  private function renderEmailBody(string $themeKey, string $url, string $langCode, $formTitle = NULL) {
    $render = [
      '#theme' => $themeKey,
      '#url' => $url,
      '#title' => $formTitle,
      '#language' => $langCode,
    ];

    $renderedEmail = $this->renderer->render($render);
    // Remove possible twig debug tags.
    $output = preg_replace(
      "#<!--[^-]*(?:-(?!->)[^-]*)*-->#",
      '',
     $renderedEmail);

    return $output;
  }

  /**
   * Generates an url for a submission id.
   *
   * @param string $submission_id
   *   Submission id.
   */
  private function generateSubmissionLink(string $submission_id) {
    return Url::fromRoute(
      'form_tool_share.view_submission',
      ['submission_id' => $submission_id],
      [
        'attributes' => [
          'data-drupal-selector' => 'form-submitted-ok',
        ],
        'absolute' => TRUE,
      ]
    );
  }

}
