<?php declare(strict_types = 1);

namespace Drupal\bunny_stream\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Bunny Stream' formatter.
 *
 * This plugin never should be used out of Media, loads information
 * of Media Source to obtain required data.
 *
 * @FieldFormatter(
 *   id = "bunny_stream_embed",
 *   label = @Translation("Bunny Stream Embed"),
 *   field_types = {"string"},
 * )
 */
class BunnyStreamEmbedFormatter extends FormatterBase {

  /**
   * Constructor for the plugin.
   *
   * @param string $plugin_id
   *    The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *    The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *    The definition of the field to which the formatter is associated.
   * @param array $settings
   *    The formatter settings.
   * @param string $label
   *    The formatter label display setting.
   * @param string $view_mode
   *    The view mode.
   * @param array $third_party_settings
   *    Any third party settings.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity_type.manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings,
    protected EntityTypeManagerInterface $entityTypeManager

  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];

    // @todo find better way to do this to don't limit this to Media.
    $field_definition = $items->getFieldDefinition();
    $bundle = $field_definition->getTargetBundle();
    /** @var \Drupal\media\Entity\MediaType $media_entity */
    $media_entity = $this->entityTypeManager->getStorage('media_type')->load($bundle);

    /** @var \Drupal\bunny_stream\Plugin\media\Source\BunnyStream $source */
    $source = $media_entity->getSource();
    /** @var \Drupal\bunny_stream\Entity\BunnyStreamLibrary $library */
    $library = $source->getLibrary();
    $library_id = $library->id();

    foreach ($items as $delta => $item) {

      $video_id = $item->value;
      $video_url = strtr(
        "//iframe.mediadelivery.net/embed/{library_id}/{video_id}",
        ["{library_id}" => $library_id, "{video_id}" => $video_id]
      );

      $url = Url::fromUri($video_url);

      $render = [
        '#theme' => "bunny_embed",
        '#url' => $url->toString(),
      ];

      $token_auth = $library->get('token_authentication_key');

      if (!empty($token_auth)) {
        $time = time() + $library->get('time');
        $security_token = hash("sha256",$token_auth . $video_id . $time);
        $url->setOptions(['query' => ['token' => $security_token, 'expires' => $time]]);

        // We can't cache videos with expiration time, so let's use BigPipe
        // to avoid cache.
        $render = [
          '#lazy_builder' => [
            '\Drupal\bunny_stream\LazyEmbedLoader::lazyLoad',
            [
              $url->toString(),
            ]
          ],
          '#create_placeholder' => TRUE,
          '#lazy_builder_preview' => [
            '#attributes' => ['id' => 'toolbar-link-preview'],
            '#type' => 'container',
            '#markup' => 'Loading video...',
          ],
        ];
      }

      $element[$delta] = $render;
    }
    return $element;
  }

}
