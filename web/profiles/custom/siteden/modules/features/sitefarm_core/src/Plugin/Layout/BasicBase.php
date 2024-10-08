<?php

namespace Drupal\sitefarm_core\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Base Layouts options for a Basic layout.
 */
abstract class BasicBase extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $sidebar_options = array_keys($this->getSidebarOptions());
    return [
      'sidebars' => end($sidebar_options),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['sidebars'] = [
      '#type' => 'select',
      '#title' => $this->t('Page Sidebars'),
      '#default_value' => $this->configuration['sidebars'],
      '#options' => $this->getSidebarOptions(),
      '#description' => $this->t('Specify if this layout uses any sidebars.'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['sidebars'] = $form_state->getValue('sidebars');
  }

  /**
   * Gets the sidebar options for the configuration form.
   *
   * The last option will be used as the default 'sidebar' configuration
   * value.
   *
   * @return string[]
   *   The sidebar options array where the keys are strings that will be used in
   *   a template to add needed classes.
   */
  protected function getSidebarOptions() {
    return [
      'none' => 'None (single column)',
      'first' => 'First Sidebar Only',
      'second' => 'Second Sidebar Only',
      'both' => 'Both First and Second Sidebars',
    ];
  }

}
