<?php

/**
 * @file
 * Theme and preprocess functions for block.
 */

use Drupal\block\Entity\Block;
use Drupal\Component\Utility\Html;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

/**
 * Implements hook_preprocess_block().
 */
function siteden_surface_sub_preprocess_block(array &$variables) {
  $bundle = (isset($variables['content']['#block_content']) ? $variables['content']['#block_content']->bundle() : FALSE);

  // Pass variable to block template to use alternate title style on blocks using block style.
  if (isset($bundle) && $bundle == 'sf_basic') {
    if (isset($variables['configuration']['block_styles']['remove_vertical_heading_bar']) && $variables['configuration']['block_styles']['remove_vertical_heading_bar']['title_style'] === 1) {
      $variables['remove_vertical_heading_bar'] = TRUE;
    }
  }

  // Pass variable to template with block type.
  if (isset($bundle)) {
    $variables['block_type'] = $bundle;
  }

  // Apply styles based on region.
  _siteden_surface_sub_block_region_styles($variables);

  // Make sure menu blocks have unique IDs in site builder layouts for accessibility reasons.
  if ($variables['elements']['#base_plugin_id'] == 'menu_block' && !isset($variables['attributes']['id'])) {
    $unique_id = Html::getUniqueId($variables['elements']['#base_plugin_id']);
    $variables['attributes']['id'] = $unique_id;
  }

  // Pass logic variables to Focal Link block.
  if (isset($bundle) && $bundle == 'sf_focal_link') {
    $content = $variables['content']['#block_content'];

    // Get field values.
    $icon_value = $content->get('field_sf_icons')->getString();
    $image_value = $content->get('field_sf_m_block_primary_img')->getString();
    $custom_value = $content->get('field_sf_icon_choice')->getString();

    // Pass figure logic to template.
    if ((empty($image_value) && empty($icon_value)) || (!empty($custom_value) && empty($icon_value))) {
      $variables['has_figure'] = FALSE;
    }
    else {
      $variables['has_figure'] = TRUE;
    }

    // Pass custom value field logic to template.
    if (empty($custom_value)) {
      $variables['is_custom'] = TRUE;
    }
    else {
      $variables['is_custom'] = FALSE;
    }
  }

  // Apply a full width style to blocks.
  if (isset($variables['configuration']['block_styles']['full_width']['full_width']) && $variables['configuration']['block_styles']['full_width']['full_width'] == 1) {
    $variables['attributes']['class'][] = 'l-full-width';
  }

  // Get the theme path for an image.
  $variables['theme_images_path'] = base_path() . \Drupal::service('extension.list.theme')->getPath('siteden_surface_sub') . '/siteden-patternlab/images/';

  // Helper vars for branding block.
  if (isset($bundle) && $bundle == 'sf_branding_block') {

    // Check if logo exists.
    if (!isset($variables['content']['field_sf_svg_image']['#items'])) {
      $variables['has_figure'] = FALSE;
    }
    else {
      $variables['has_figure'] = TRUE;
    }

    // Check if title exists.
    if (!isset($variables['content']['field_sf_title']['#items'])) {
      $variables['has_body'] = FALSE;
    }
    else {
      $variables['has_body'] = TRUE;
    }

    // Get media image url.
    if (isset($variables["content"]["field_sf_svg_image"][0])) {
      $mid = $variables["content"]["field_sf_svg_image"][0]["#media"]->id();
      $media = Media::load($mid);
      $fid = $media->getSource()->getSourceFieldValue($media);
      $file = File::load($fid);
      $uri = $file->getFileUri();
      $url = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
      $variables['svg_logo_url'] = $url;
    }
  }

  // Apply classes to person info block.
  if (isset($variables['elements']['#id']) && $variables['elements']['#id'] == 'views_block__sf_person_info_block_1') {
    $variables['attributes']['class'][] = 'panel--mobile-collapse';
    $variables['title_attributes']['class'][] = 'panel__title';
    $variables['title_attributes']['class'][] = 'u-hidden--desktop';
  }
}

/**
 * Implements hook_theme_suggestions_block_alter().
 */
function siteden_surface_sub_theme_suggestions_block_alter(array &$suggestions, array $variables) {
  // Add theme suggestions for custom blocks.
  if (isset($variables['elements']['content']['#block_content'])) {
    $pattern = 'block__' . $variables['elements']['content']['#block_content']->bundle();

    // Add the suggestion after normal block suggestions but before single
    // specific id suggestions.
    array_splice($suggestions, 2, 0, $pattern);
  }

  $patterns = [];
  $plugin_id = $variables['elements']['#plugin_id'];
  $base_plugin_id = $variables['elements']['#base_plugin_id'];

  // Everything below requires access the the Block class.
  if (empty($variables['elements']['#id'])) {
    return;
  }
  $block = Block::load($variables['elements']['#id']);

  // Exit out if a block can not be found.
  if (!$block) {
    return;
  }

  $region = $block->getRegion();

  // Search Bar.
  if ($plugin_id == 'search_form_block' && $region == 'navigation') {
    $patterns[] = 'block__search_bar';
  }

  // Theme all menus by region.
  if ($base_plugin_id == 'system_menu_block' || $base_plugin_id == 'menu_block') {
    $patterns[] = 'block__menu__region_' . $region;
    $patterns[] = 'block__menu__' . $variables['elements']['#derivative_plugin_id'] . '__region_' . $region;
  }

  // Add the suggestion after normal block suggestions but before single
  // specific id suggestions.
  array_splice($suggestions, 2, 0, $patterns);
}

/**
 * Style blocks within a region.
 *
 * @param array $variables
 *   The variable sent to twig templates.
 */
function _siteden_surface_sub_block_region_styles(array &$variables) {
  // Exit if there is no block ID (likely because it is a field block).
  if (empty($variables['elements']['#id'])) {
    $variables['class_panel'] = TRUE;
    return;
  }

  $block = Block::load($variables['elements']['#id']);

  // Exit out if a block can not be found.
  if (!$block) {
    return;
  }

  // Get the region.
  $region = $block->getRegion();

  // Get the plugin id.
  $plugin_id = $block->getPlugin()->getPluginId();

  // Add in the region so it can be used in suggestions_menu_alter hooks.
  $variables['content']['#attributes']['region'] = $region;

  // Primary Nav / Main Menu.
  if ($region == 'navigation'
    && preg_match('/.*menu_block.*/', $plugin_id)) {
    $variables['attributes']['class'][] = 'nav-primary';
  }

  // Search Forms.
  if ($plugin_id == 'search_form_block') {
    // Search Bar.
    if ($region == 'navigation') {
      // Main nav search form.
      $variables['content']['#attributes']['class'][] = 'search-bar__form';
      $variables['content']['keys']['#title'] = t('Search the site:');
      $variables['content']['keys']['#attributes']['placeholder'] = 'Search …';
      $variables['content']['keys']['#attributes']['required'] = 'required';
      $variables['content']['keys']['#attributes']['class'][] = 'search-bar__input';
      $variables['content']['actions']['submit']['#attributes']['class'][] = 'btn';
      $variables['content']['actions']['submit']['#attributes']['class'][] = 'search-bar__submit';
    }
    // Regular search form.
    else {
      $variables['content']['#attributes']['class'][] = 'search-form';
      $variables['content']['keys']['#attributes']['placeholder'] = 'Search for ...';
      $variables['content']['keys']['#attributes']['required'] = 'required';
      $variables['content']['keys']['#attributes']['class'][] = 'search-form__input';
      $variables['content']['actions']['submit']['#attributes']['class'][] = 'search-form__btn';
    }
  }

  // Add .panel if Content or Sidebar regions.
  $box_regions = [
    'sidebar_first',
    'sidebar_second',
    'content',
  ];
  if (in_array($region, $box_regions)) {
    $variables['class_panel'] = (isset($variables['class_panel'])) ? $variables['class_panel'] : TRUE;
  }
}
