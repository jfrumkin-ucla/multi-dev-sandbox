<?php

namespace Drupal\sitefarm_core\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;

/**
 * Layouts options for a Basic layout.
 */
class Basic extends BasicBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config['show_title'] = FALSE;
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
//    $form['show_title'] = [
//      '#type' => 'checkbox',
//      '#title' => $this->t('Show Title'),
//      '#default_value' => $this->configuration['show_title'],
//      '#description' => $this->t('Check this box if a title region should be added above the columns.'),
//    ];

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['show_title'] = $form_state->getValue('show_title');
  }

  /**
   * {@inheritdoc}
   */
  protected function getSidebarOptions() {
    $options = parent::getSidebarOptions();

    $options += [
      'left_split' => 'Left Split (first region will go above content in mobile)',
      'right_split' => 'Right Split (first region will go above content in mobile)',
    ];

    return $options;
  }

}
