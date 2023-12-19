<?php

namespace Drupal\bunny_stream;

use GuzzleHttp\Client;

/**
 * Execute request to Bunny.net API to manage collections.
 *
 * For more information about the API check the documentation:
 * https://docs.bunny.net/reference/api-overview
 */
class CollectionManager {

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

}
