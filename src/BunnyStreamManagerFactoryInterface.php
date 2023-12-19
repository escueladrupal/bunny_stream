<?php

namespace Drupal\bunny_stream;

interface BunnyStreamManagerFactoryInterface {

  public function getVideoManager(string $config_id);

  public function getCollectionManager(string $config_id);
}
