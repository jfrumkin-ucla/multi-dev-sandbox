<?php

namespace Drupal\siteden_node_author\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class SitedenNodeAuthorSettingsForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'siteden_node_author.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'siteden_node_author_settings_form';
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
    $num_anonymous_nodes = $this->getAnonymousNodes();
    $query = \Drupal::entityQuery('user')
      ->accessCheck(TRUE);
    $results = $query->execute();
    $uid_options = [];
    foreach($results as $result_id => $uid){
      $user = \Drupal\user\Entity\User::load($uid); 
      $uid_options[$uid] = $user->getDisplayName();
    }
    $uid_options[0] = 'Select User Account';
    ksort($uid_options);

    $form['anonymous_author_override_uid'] = [
      '#type' => 'select',
      '#title' => $this->t('Select User Account'),
      '#options' => $uid_options,
      '#default_value' => $config->get('anonymous_author_override_uid'),      
    ];  

    if($num_anonymous_nodes > 0){
      $node_verbage = "node";
      if($num_anonymous_nodes > 1){
        $node_verbage = "nodes";
      }
      $form['assign_anonymous_nodes'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Assign all '.$num_anonymous_nodes.' '.$node_verbage.' currently authored by Anonymous to this user?'),
        '#default_value' => TRUE,      
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('anonymous_author_override_uid', $form_state->getValue('anonymous_author_override_uid'))
      ->save();
    $uid = (int)$form_state->getValue('anonymous_author_override_uid');
    $message = "Anonymous node author override has been set";
    if($form_state->getValue('assign_anonymous_nodes') == 1){
      $query = \Drupal::entityQuery('node')
        ->condition('uid', 0, '=')
        ->accessCheck(TRUE);
      $results = $query->execute();
      $count = 0;
      foreach($results as $nid){
        $count++;
        \Drupal::entityTypeManager()->getStorage('node')->load($nid)->set('uid', $uid)->save();
      }
      $node_verbage = "node";
      $action_vergage = "has";
      if($count > 1){
        $node_verbage = "nodes";
        $action_vergage = "have";
      }
      $message .= ", and ".$count." anonymous ".$node_verbage." ".$action_vergage." been updated";
    }
    $message .= ".";
    \Drupal::messenger()->addMessage($this->t($message), 'status', TRUE);
    parent::submitForm($form, $form_state);
  }


  public function getAnonymousNodes(){
    $query = \Drupal::entityQuery('node')
      ->condition('uid', 0, '=')
      ->accessCheck(TRUE);
    $results = $query->execute();
    return count($results);
  }
}
