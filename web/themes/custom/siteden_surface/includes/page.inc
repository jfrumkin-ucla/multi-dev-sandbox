<?php

/**
 * @file
 * Theme and preprocess functions for page.
 */

use Drupal\node\NodeInterface;

/**
 * Implements hook_page_attachments_alter().
 */
function siteden_surface_page_attachments_alter(array &$page) {
  // Tell IE to use latest rendering engine (not to use compatibility mode).
  $ie_edge = [
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => [
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge,chrome=1',
    ],
  ];
  $page['#attached']['html_head'][] = [$ie_edge, 'ie_edge'];

  // iOS full screen enable.
  $meta_apple = [
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => [
      'http-equiv' => 'apple-mobile-web-app-capable',
      'content' => 'yes',
    ],
  ];
  $page['#attached']['html_head'][] = [$meta_apple, 'meta_apple'];
}

/**
 * Implements hook_preprocess_page() for page.html.twig.
 */
function siteden_surface_preprocess_page(array &$variables) {
  $variables['site_name'] = \Drupal::config('system.site')->get('name');

  // Get the theme path for an image.
  $variables['theme_images_path'] = base_path() . \Drupal::service('extension.list.theme')->getPath('siteden_surface') . '/images/';

  // Hide the event, and photo gallery category filter blocks on node pages.
  // @todo Use block visibility to negate content types when bug is fixed that
  // causes visibility rule to break on view pages.
  // https://www.drupal.org/project/block_visibility_groups/issues/2820631
  $target_nodes = [
    'sf_event',
    'sf_photo_gallery',
    'sf_person',
  ];

  // Make sure the node object is available and set, then get type/bundle.
  if (isset($variables['node'])) {
    $node = $variables['node'];

    // If the object is not a node--for example, when viewing a revision--first load the node.
    if (!$node instanceof NodeInterface) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($node);
    }
    $type = $node->getType();
  }

  // Remove the category filter block on target nodes.
  if (isset($type) && in_array($type, $target_nodes)) {
    $sidebar_content_exists = FALSE;

    if ($type == 'sf_event') {
      unset($variables['page']['sidebar_first']['views_block__sf_events_category_filter_block_1']);
      unset($variables['page']['sidebar_first']['exposedformsf_events_listingpage_1']);
    }
    if ($type == 'sf_photo_gallery') {
      unset($variables['page']['sidebar_second']['views_block__sf_photo_gallery_category_filter_block_1']);
    }
    if ($type == 'sf_person') {
      unset($variables['page']['sidebar_first']['exposedformsf_person_directorypage_1']);
    }

    // After removing filter block check if first sidebar contains any other
    // blocks.
    foreach (array_keys($variables['page']['sidebar_first']) as &$item) {
      $first_char = substr($item, 0, 1);

      if ($first_char !== '#') {
        $sidebar_content_exists = TRUE;
      }
    }

    // If no other blocks in first sidebar remove it.
    if (!$sidebar_content_exists) {
      unset($variables['page']['sidebar_first']);
    }
  }

  // Set sidebar split.
  if (isset($type) && $type == 'sf_person') {
    $variables['right_split'] = TRUE;
  }

  // Remove regions from landing page type.
  if (isset($type) && $type == 'sf_landing') {
    unset($variables['page']['sidebar_first']);
    unset($variables['page']['sidebar_second']);
    unset($variables['page']['title']);
  }

  if (isset($type) && $type == 'sf_page') {
    $formatting_options = $node->get('field_sf_display_options')->getValue();
    $formatting_options = array_column($formatting_options, 'value');

    $media_id = $node->get('field_sf_featured_media')->getValue()[0]['target_id'] ?? NULL;

    if (in_array("title_banner", $formatting_options)) {
      $variables['format__title_banner'] = TRUE;
      $variables['format__title_image_field'] = $media_id;
    }
    if (in_array("hide_sidebars", $formatting_options)) {
      $variables['format__hide_sidebars'] = TRUE;
      unset($variables['page']['sidebar_first']);
      unset($variables['page']['sidebar_second']);
    }
  }
}
