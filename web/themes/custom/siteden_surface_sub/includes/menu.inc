<?php

/**
 * @file
 * Theme and preprocess functions for menus.
 */

/**
 * Implements hook_theme_suggestions_menu_alter().
 */
function siteden_surface_sub_theme_suggestions_menu_alter(array &$suggestions, array $variables) {
  if (isset($variables['attributes']['region'])) {
    $suggestions[] = 'menu__region_' . $variables['attributes']['region'];
    $suggestions[] = 'menu__' . $variables['menu_name'] . '__region_' . $variables['attributes']['region'];
  }
}
