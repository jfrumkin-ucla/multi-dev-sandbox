<?php

/**
 * @file
 * Theme and preprocess functions for filter_caption.
 */

use Drupal\Component\Render\MarkupInterface;

/**
 * Implements hook_preprocess_filter_caption().
 */
function siteden_surface_sub_preprocess_filter_caption(array &$variables) {
  // Exit if this is not a media entity.
  if ($variables['tag'] != 'drupal-media' && !isset($variables['node']) && !($variables['node'] instanceof MarkupInterface)) {
    return;
  }

  $markup = $variables['node']->__toString();

  preg_match('/data-entity-uuid="([^"]*)"/', $markup, $matches);
  $uuid = $matches[1];

  /** @var \Drupal\media\Entity\Media $media */
  $media = \Drupal::service('entity.repository')->loadEntityByUuid('media', $uuid);

  if (!$media) {
    return;
  }

  // Add the .caption--video class if this is a video media type. Do not do this
  // if there is an alignment.
  if ($media->bundle() == 'sf_video_media_type') {
    if (strpos($variables['classes'], 'align') === FALSE) {
      $variables['classes'] .= ' caption--video';
    }
    else {
      $variables['classes'] .= ' caption--video-align';
    }
  }
}
