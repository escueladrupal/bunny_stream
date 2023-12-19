<?php

namespace Drupal\bunny_stream;

use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * This class is used to enabled BigPipe with videos with expiration time.
 *
 * We can't cache videos with expiration time, to let's use
 * BigPipe to send the video with the expiration date.
 */
class LazyEmbedLoader implements TrustedCallbackInterface {

  public static function lazyLoad(string $url): array {
    return [
      '#theme' => "bunny_embed",
      '#url' => $url,
      '#cache' => [
        'max-age' => 0
      ]
    ];
  }

  public static function trustedCallbacks() {
    return [
      'lazyLoad',
    ];
  }
}
