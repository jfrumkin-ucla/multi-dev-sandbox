<?php

/**
 * @file
 * Theme and preprocess functions for responsive embed templates.
 */

/**
 * Implements template_preprocess_responsive_embed().
 */
function siteden_surface_sub_preprocess_responsive_embed(array &$variables) {
  $embed = $variables['url_output'];

  // Exit if the embed is already doing lazy loading.
  if (strpos($embed, 'loading=') !== FALSE) {
    return;
  }

  $embed = preg_replace('/><\/iframe>/', ' loading="lazy"></iframe>', $embed);
  $variables['url_output'] = $embed;
}
