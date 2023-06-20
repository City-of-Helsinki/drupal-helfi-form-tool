<?php

namespace Drupal\webform_formtool_handler\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelFactory;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\webform_formtool_handler\Event\WebformSubmissionEvent;
use GuzzleHttp\ClientInterface;

/**
 * Subscribes to webform submission event and sends notification to service.
 */
class WebformSubmissionSubscriber implements EventSubscriberInterface {

  /**
   * Constructs a WebformSubmissionSubscriber listener.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Http client. Guzzle.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $logger
   *   Logger factory.
   */
  public function __construct(
    private ClientInterface $http_client,
    private LoggerChannelFactory $logger
    ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[WebformSubmissionEvent::SUBMISSION_EVENT][] = ['onSubmissionCreate'];
    return $events;
  }

  /**
   * Send POST request to service.
   *
   * @param WebformSubmissionEvent $event
   *   A WebformSubmissionEvent event.
   * @throws GuzzleException
   */
  public function onSubmissionCreate(WebformSubmissionEvent $event) {

    try {
      $formToolSubmissionId = $event->getAtvDocument()->getId();
      $endpoint = getenv('FORM_SUBMISSION_NOTIFICATION_ENDPOINT');
      $password = getenv('FORM_SUBMISSION_NOTIFICATION_ENDPOINT_PASSWORD');

      if (!$endpoint || !$password || !$formToolSubmissionId) {
        return;
      }

      $response = $this->http_client->request('POST', $endpoint, [
        'json' => [
          'formID' => $formToolSubmissionId,
        ],
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Basic ' . $password,
        ],
      ]);

      $statusCode = $response->getStatusCode();

      $this->logger->get('webform_formtool_handler')->info('Sent notification for @submissionId (@statusCode)', [
        '@submissionId' => $formToolSubmissionId,
        '@statusCode' => $statusCode,
      ]);
    }
    catch (\Exception $e) {
      $this->logger->get('webform_formtool_handler')->error('Failed to send form submission notification event for @submissionId (@statusCode). Error: @error', [
        '@submissionId' => $formToolSubmissionId,
        '@statusCode' => $e->getCode(),
        '@error' => $e->getMessage(),
      ]);
    }
  }

}
