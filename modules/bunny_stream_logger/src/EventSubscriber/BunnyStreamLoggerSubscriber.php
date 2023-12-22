<?php declare(strict_types = 1);

namespace Drupal\bunny_stream_logger\EventSubscriber;

use Drupal\bunny_stream\Event\WebhookEvent;
use Drupal\Core\Database\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @todo Add description for this subscriber.
 */
final class BunnyStreamLoggerSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected Connection $database
  ) {}

  public function onWebhook(WebhookEvent $event) {
    $payload = $event->getPayload();

    $this->database
      ->insert('bunny_stream_logger')
      ->fields([
        'status' => $payload['Status'],
        'library' => $payload['VideoLibraryId'],
        'video' => $payload['VideoGuid'],
        'timestamp' => time(),
      ])
      ->execute();
  }
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      WebhookEvent::WEBHOOK => ['onWebhook'],
    ];
  }

}
