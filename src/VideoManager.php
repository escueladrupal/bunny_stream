<?php

namespace Drupal\bunny_stream;

use GuzzleHttp\Client;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Execute request to Bunny.net API to manage videos.
 *
 * For more information about the API check the documentation:
 * https://docs.bunny.net/reference/api-overview
 */
class VideoManager {

  /**
   * Domain of bunny API.
   */
  public CONST DOMAIN = "https://video.bunnycdn.com";

  /**
   * Get Video, GET.
   */
  public CONST GET = "/library/{libraryId}/videos/{videoId}";

  /**
   * Update video, POST.
   */
  public CONST UPDATE = "/library/{libraryId}/videos/{videoId}";

  /**
   * Delete Video, DELETE.
   */
  public CONST DELETE = "/library/{libraryId}/videos/{videoId}";

  /**
   * Upload video, PUT.
   */
  public CONST UPLOAD = "/library/{libraryId}/videos/{videoId}";

  /**
   * Get video heatmap, GET.
   */
  public CONST HEATMAP = "/library/{libraryId}/videos/{videoId}/heatmap";

  /**
   * Get video statistics, GET.
   */
  public CONST STATISTICS = "/library/{libraryId}/statistics";

  /**
   * Reencode one video, POST.
   */
  public CONST REENCODE = "/library/{libraryId}/videos/{videoId}/reencode";

  /**
   * List videos of the library, GET.
   */
  public CONST LIST = "/library/{libraryId}/videos";

  /**
   * Create new video on the library, POST.
   */
  public CONST CREATE = "/library/{libraryId}/videos";

  /**
   * Set thumbail of video, POST.
   */
  public CONST THUMBNAIL = "/library/{libraryId}/videos/{videoId}/thumbnail";

  /**
   * Fetch video, POST.
   */
  public CONST FETCH = "/library/{libraryId}/videos/fetch";

  /*
   * Add caption to video, POST.
   */
  public CONST CAPTION = "/library/{libraryId}/videos/{videoId}/captions/{srclang}";

  /**
   * Delete caption from video, DELETE.
   */
  public CONST DELETE_CAPTION = "/library/{libraryId}/videos/{videoId}/captions/{srclang}";

  /**
   * Constructor of the class.
   *
   * @param \GuzzleHttp\Client $client
   *   The http client to execute the requests.
   * @param \Drupal\bunny_stream\BunnyStreamLibraryInterface $config
   *   The library from where load the data to execute the request.
   */
  public function __construct(
    protected Client $client,
    protected BunnyStreamLibraryInterface $config
  ) {}

  /**
   * @param string $video_id
   *
   * @return ?\Drupal\bunny_stream\BunnyVideo
   *   Loaded video.
   */
  public function getVideo(string $video_id): ?BunnyVideo {
    $url = $this->generateUrl(self::GET, ["{libraryId}" => $this->config->id(), "{videoId}" => $video_id]);

    try {
      $response = $this->executeRequest('GET', $url);

      if ($response->getStatusCode() === 200) {

        $body = $response->getBody();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        /** @var \Drupal\bunny_stream\BunnyVideo $bunnyVideo */
        $bunnyVideo = $serializer->deserialize($body, BunnyVideo::class ,'json');

        return $bunnyVideo;
      }
    }
    catch (\Exception $e) {
      // Log the error here.
    }

    return NULL;
  }

  /**
   * Execute the request to Bunny.net API.
   *
   * @param string $method
   *   The method for the request.
   * @param string $url
   *   The URL where execute the request.
   * @param array $options
   *   Different options for the url, like headers or query parameters.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   Response of the request.
   */
  protected function executeRequest(string $method, string $url, array $options = []): object {
    return $this->client->request($method, self::DOMAIN . $url, [
      'headers' => [
        'AccessKey' => $this->config->get('api_key'),
        'accept' => 'application/json',
      ],
    ]);
  }

  /**
   * Replace the given values on the url to generate the correct URL.
   *
   * @param string $url
   *   Url to replace with the values.
   * @param array $values
   *   Values to replace in the url.
   *
   * @return string
   *   The url with the values replaced.
   */
  protected function generateUrl(string $url, array $values): string {
    return strtr(
      $url,
      $values
    );
  }

}
