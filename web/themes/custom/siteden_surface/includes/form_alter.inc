<?php

/**
 * @file
 * Form alter functions.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_alter().
 */
function siteden_surface_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  // Add appropriate classes to the Search Form page inputs.
  if ($form_id == 'search_form') {
    // Remove the .search-form class if present.
    if (!empty($form['#attributes']['class'])) {
      $search_class = array_search('search-form', $form['#attributes']['class']);
      if ($search_class !== FALSE) {
        unset($form['#attributes']['class'][$search_class]);
      }
    }

    // Remove the inline container class if present.
    if (!empty($form['basic']['#attributes']['class'])) {
      $container_class = array_search('container-inline', $form['basic']['#attributes']['class']);
      if ($container_class !== FALSE) {
        unset($form['basic']['#attributes']['class'][$container_class]);
      }
    }

    // Remove the search help link.
    // If search is Google CSE, remove search help & add the powered by CSE image.
    if (in_array('google-cse', $form['#attributes']['class'])) {
      unset($form['help_link']);
      $form['basic']['#suffix'] = '<div><span class="fon">' . t('Powered by') . '</span> ' . t('Google Custom Search') . '</div>';
    }

    $form['basic']['#attributes']['class'][] = 'search-form';
    $form['basic']['keys']['#attributes']['class'][] = 'search-form__input';
    $form['basic']['keys']['#attributes']['placeholder'] = 'Search for ...';
    $form['basic']['keys']['#attributes']['required'] = 'required';
    $form['basic']['submit']['#attributes']['class'][] = 'search-form__btn';
  }

  // Make sure views exposed form uses the correct action path so that the wrong
  // view is not shown as a reuslt of filtering the view using the exposed
  // filters.
  if ($form_id == "views_exposed_form"
    && ($form["#id"] == "views-exposed-form-sf-articles-recent-page-2"
      || $form["#id"] == "views-exposed-form-taxonomy-term-page-1")) {
    /** @var \Drupal\Core\Path\CurrentPathStack $current_path */
    $current_path = \Drupal::service('path.current');
    if ($form["#action"] != $current_path->getPath()) {
      $form["#action"] = $current_path->getPath();
    }
  }

}
