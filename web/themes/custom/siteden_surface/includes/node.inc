<?php

/**
 * @file
 * Theme and preprocess functions for node template.
 */

/**
 * Implements hook_preprocess_node().
 */
function siteden_surface_preprocess_node(&$variables) {
  $node = $variables['node'];
  $type = $node->getType();
  $vm = $variables['view_mode'];

  // If this is an article node and the view mode is listing, don't show the
  // submitted info.
  if ($type == 'sf_article' && $vm == 'listing') {
    $variables['display_submitted'] = FALSE;
  }

  // Pass user full name to article node.
  if ($type == 'sf_article') {
    $variables['author_full_name'] = $node->getOwner()->field_sf_full_name->value;
  }

  // Brand the category link for articles.
  if ($type == 'sf_article') {
    $article_cat = $node->get('field_sf_article_category')->getValue();
    $article_cat_tid = $article_cat[0]['target_id'] ?? FALSE;

    if (isset($article_cat_tid) && $article_cat_tid !== FALSE) {
      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($article_cat_tid);
      $category_color_val = $term->get('field_sf_brand_color')->value;

      $variables['article_cat_brand_class'] = $category_color_val;
    }
  }

}
