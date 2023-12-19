<?php

namespace Drupal\bunny_stream;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\SerializerInterface;

class BunnyStreamManagerFactory implements BunnyStreamManagerFactoryInterface {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected Client $client
  ) {}

  public function getVideoManager(string $config_id) {
    /** @var \Drupal\bunny_stream\BunnyStreamLibraryInterface|null $config */
    $config = $this->loadConfig($config_id);

    if (!is_null($config_id)) {
      return new VideoManager($this->client, $config);
    }
  }

  public function getCollectionManager(string $config_id) {
    $config = $this->loadConfig($config_id);
  }

  private function loadConfig(string $config_id) {
    return $this->entityTypeManager->getStorage('bunny_stream_library')->load($config_id);
  }

}
