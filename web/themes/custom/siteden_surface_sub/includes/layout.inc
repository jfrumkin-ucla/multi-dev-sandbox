<?php

/**
 * @file
 * Theme and preprocess functions for layout.
 */

/**
 * Implements hook_preprocess_layout().
 */
function siteden_surface_sub_preprocess_layout(&$variables) {
  if (isset($variables['configuration']['block_styles']['full_width']['full_width']) && $variables['configuration']['block_styles']['full_width']['full_width'] == 1) {
    $variables['attributes']['class'][] = 'l-full-width';
  }
}
