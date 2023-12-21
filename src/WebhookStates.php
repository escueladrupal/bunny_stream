<?php

namespace Drupal\bunny_stream;

/**
 * List of states for webhooks.
 *
 * For more information check the documentation:
 * https://docs.bunny.net/docs/stream-webhook#status-list
 */
enum WebhookStates: int {

  /**
   * Is not posible to translate this options here, move to another place.
   */
  public CONST STATES = [
    0 => 'Queued',
    1 => 'Processing',
    2 => 'Encoding',
    3 => 'Finished',
    4 => 'Resolution finished',
    5 => 'Failed',
    6 => 'Presigned upload stated',
    7 => 'Presigned upload finished',
    8 => 'Presigned upload failed',
    9 => 'Caption generated',
    10 => 'Title or description generated',
  ];

  case QUEUED = 0;
  case PROCESING = 1;
  case ENCODING = 2;
  CASE FINISHED = 3;
  CASE RESOLUTION_FINISHED = 4;
  CASE FAILED = 5;
  CASE PRESIGNED_UPLOAD_STATED = 6;
  CASE PRESIGNED_UPLOAD_FINISHED = 7;
  CASE PRESIGNED_UPLOAD_FAILED = 8;
  CASE CAPTION_GENERATED = 9;
  CASE TITLE_OR_DESCRIPTION_GENERATED = 10;
}
