<?php

/**
 * @file
 * Theme and preprocess functions for image templates.
 */

/**
 * Implements template_preprocess_image().
 */
function siteden_surface_sub_preprocess_image(array &$variables) {
  // Required attribute for enabling native lazy-loading.
  $variables['attributes']['loading'] = 'lazy';

  // If an image alt value is empty, remove the alt attribute entirely to avoid accessibility errors.
  if (isset($variables['alt']) && $variables['alt'] === '') {
    $variables['attributes']['alt'] = NULL;
    unset($variables['alt']);
  }
}
