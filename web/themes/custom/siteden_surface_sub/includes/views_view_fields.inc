<?php

/**
 * @file
 * Theme and preprocess functions for views_view_fields.
 */

/**
 * Implements hook_preprocess_hook().
 */
function siteden_surface_sub_preprocess_views_view_fields(&$variables) {
  $view = $variables['view'];
  $view_id = $view->id();

  if (isset($view_id) && $view_id == 'sf_person_info') {
    $profile_img = $variables['fields']['field_sf_m_primary_image'];
    $attr = $profile_img->element_attributes;
    $row = $variables['row'];
    $entity = $row->_entity;
    $img = $entity->get('field_sf_m_primary_image')->getValue();

    if (!empty($img)) {
      $attr->addClass('u-space-mb');
    }
  }
}
