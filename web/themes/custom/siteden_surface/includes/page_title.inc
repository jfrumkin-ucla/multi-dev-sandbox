<?php

/**
 * @file
 * Theme and preprocess functions for page title.
 */

/**
 * Implements hook_preprocess_page_title().
 */
function siteden_surface_preprocess_page_title(&$variables) {
  $term = \Drupal::routeMatch()->getParameter('taxonomy_term');

  // Load the term entity from current route.
  if (!empty($term) && is_object($term)) {

    $vocab = $term->bundle();

    // Only do this for the article category vocab.
    if ($vocab === 'sf_article_category') {
      $brand_color = $term->get('field_sf_brand_color')->value;
      $variables['brand_color'] = $brand_color;
    }
  }
}

/**
 * Add taxonomy vocabulary suggestions, e.g. page-title--article-category.html.twig.
 */
function siteden_surface_theme_suggestions_page_title_alter(array &$suggestions, array $variables) {
  $term = \Drupal::routeMatch()->getParameter('taxonomy_term');

  if (!empty($term) && is_object($term)) {
    array_splice($suggestions, 1, 0, 'page_title__' . $term->bundle());
  }
}
