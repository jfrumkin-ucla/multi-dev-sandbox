<?php

namespace Drupal\siteden_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class StatusAlertMessageSettingsForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'status_alert_message.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'status_alert_message_settings_form';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['status_alert_message_normal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Normal Operations H2 Text'),
      '#default_value' => $config->get('status_alert_message_normal'),      
    ];  
    $form['status_alert_subtext_normal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Normal Operations Default Subtext Message'),
      '#default_value' => $config->get('status_alert_subtext_normal'),      
    ];  
    $form['status_alert_message_modified'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Modified Operations H2 Text'),
      '#default_value' => $config->get('status_alert_message_modified'),      
    ];  
    $form['status_alert_subtext_modified'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Modified Operations Default Subtext Message'),
      '#default_value' => $config->get('status_alert_subtext_modified'),      
    ];  
    $form['status_alert_message_limited'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Limited Operations H2 Text'),
      '#default_value' => $config->get('status_alert_message_limited'),      
    ];  
    $form['status_alert_subtext_limited'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Limited Operations Default Subtext Message'),
      '#default_value' => $config->get('status_alert_subtext_limited'),      
    ];  
    $form['status_alert_message_emergency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Emergency on Campus H2 Text'),
      '#default_value' => $config->get('status_alert_message_emergency'),      
    ];  
    $form['status_alert_subtext_emergency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Emergency Operations Default Subtext Message'),
      '#default_value' => $config->get('status_alert_subtext_emergency'),      
    ];  
    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_message_normal', $form_state->getValue('status_alert_message_normal'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_message_modified', $form_state->getValue('status_alert_message_modified'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_message_limited', $form_state->getValue('status_alert_message_limited'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_message_emergency', $form_state->getValue('status_alert_message_emergency'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_subtext_normal', $form_state->getValue('status_alert_subtext_normal'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_subtext_modified', $form_state->getValue('status_alert_subtext_modified'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_subtext_limited', $form_state->getValue('status_alert_subtext_limited'))
      ->save();

    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('status_alert_subtext_emergency', $form_state->getValue('status_alert_subtext_emergency'))
      ->save();

      parent::submitForm($form, $form_state);
  }
}
