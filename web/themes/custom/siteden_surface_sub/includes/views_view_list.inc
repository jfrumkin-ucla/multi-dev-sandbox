<?php

/**
 * @file
 * Theme and preprocess functions for views_view_list.
 */

use Drupal\Core\Template\Attribute;

/**
 * Implements template_preprocess_views_view_list().
 */
function siteden_surface_sub_preprocess_views_view_list(array &$variables) {
  $rows = $variables['rows'];

  // Add category brand color classes to article filter.
  $view = $variables['view'];

  if ($view->element['#name'] == 'sf_article_filter' && $view->element['#display_id'] == 'block_2') {

    // Loop through each row and add a brand class.
    foreach ($rows as $key => $row) {
      // Add branding class.
      $brand_color = $row['content']['#row']->taxonomy_term_field_data_node__field_sf_article_category__ta;
      $rows[$key]['attributes']->addClass($brand_color);
    }
  }

  // Add active class to filter blocks on events, articles, and photo galleries.
  if (!empty($variables['list']['attributes'])
    && $variables['list']['attributes'] instanceof Attribute
    && $variables['list']['attributes']->hasClass('category-filter')) {

    // Set the cache tag.
    $variables['#cache']['contexts'][] = 'url';

    // Check filter parameter type.
    $term = FALSE;
    $tax_term = \Drupal::routeMatch()->getParameter('taxonomy_term');
    $art_cat = \Drupal::routeMatch()->getParameter('article_category');

    // Get the term info to be matched to current term.
    if (isset($tax_term) && is_numeric($tax_term)) {
      $term = $tax_term;
      $is_tax_term = TRUE;
    }
    elseif (isset($art_cat)) {
      $article_param = $art_cat;
      $article_term_name = str_replace('-', ' ', $article_param);
      $article_term_props = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties([
          'name' => $article_term_name,
          'vid' => 'sf_article_category',
        ]);
      foreach ($article_term_props as &$item) {
        $term = $item->id();
        break;
      }
      $is_tax_term = FALSE;
    }

    // Loop through filters and add active class if currently filtering by that term.
    if ($term) {
      $current_tid = is_object($term) ? $term->tid->value : $term;

      // Loop through each row and add an active class if the path is the current one.
      foreach ($rows as $key => $row) {
        $term = $row['content']['#row']->_entity;

        if ($is_tax_term) {
          $row_tid = $term->tid->value;
        }
        else {
          $row_tid = $term
            ->getFields()['field_sf_article_category']
            ->getValue()[0]['target_id'];
        }

        if ($current_tid == $row_tid) {
          $rows[$key]['attributes']->addClass('category-filter__list-item--active');
        }
      }
    }
  }
}
