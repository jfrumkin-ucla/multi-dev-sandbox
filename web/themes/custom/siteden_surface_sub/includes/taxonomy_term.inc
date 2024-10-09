<?php

/**
 * @file
 * Theme and preprocess functions for taxonomy_term.
 */

/**
 * Implements template_preprocess_taxonomy_term().
 */
function siteden_surface_sub_preprocess_taxonomy_term(&$variables) {
  // Embed the sub units views display into the Units Term output.
  $bundle = $variables["elements"]["#taxonomy_term"]->bundle();
  if ($bundle == 'sf_units') {
    array_push($variables["content"],
      [
        'sub_units' => views_embed_view('units', 'embed_1', $variables["elements"]["#taxonomy_term"]->id()),
        '#weight' => 0,
      ]
    );
  }
}
