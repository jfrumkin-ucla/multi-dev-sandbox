<?php

namespace Drupal\siteden_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a site status alert block.
 *
 * @Block(
 *   id = "bso_status_alert_block",
 *   admin_label = @Translation("Status Alert Block"),
 *   context_definitions = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Node"))
 *   }
 * )
 */
class BsoStatusAlertBlock extends BlockBase {



public function blockForm($form, FormStateInterface $form_state) {
    $status_options = [
      'normal' => 'Normal Operations',
      'modified' => 'Modified Operations',
      'limited' => 'Limited Operations',
      'emergency' => 'Emergency on Campus'
    ];
    $form['bso_status_alert_block']['settings']['site_status'] = [
      '#type' => 'select',
      '#title' => $this->t('Current Status'),
      '#options' => $status_options,
      '#default_value' => $this->configuration['site_status'],
    ];
    if($this->configuration['status_message']){
      $default = $this->configuration['status_message'];
    } else {
      $default = \Drupal::config('status_alert_message.settings')->get('status_alert_subtext_normal');
    }
    $form['bso_status_alert_block']['settings']['status_message']['normal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status Message Subtext'),
      '#default_value' => $default,
      '#states' => [
        // Show this textfield only if the radio 'other' is selected above.
        'visible' => [
          // Don't mistake :input for the type of field or for a css selector --
          // it's a jQuery selector. 
          // You can always use :input or any other jQuery selector here, no matter 
          // whether your source is a select, radio or checkbox element.
          // in case of radio buttons we can select them by thier name instead of id.
          ':input[name="field_sf_block[0][settings][bso_status_alert_block][settings][site_status]"]' => ['value' => 'normal'],
        ],
      ],
    ];


    if($this->configuration['status_message']){
      $default = $this->configuration['status_message'];
    } else {
      $default = \Drupal::config('status_alert_message.settings')->get('status_alert_subtext_modified');
    }
    $form['bso_status_alert_block']['settings']['status_message']['modified'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status Message Subtext'),
      '#default_value' => $default,
      '#states' => [
        // Show this textfield only if the radio 'other' is selected above.
        'visible' => [
          // Don't mistake :input for the type of field or for a css selector --
          // it's a jQuery selector. 
          // You can always use :input or any other jQuery selector here, no matter 
          // whether your source is a select, radio or checkbox element.
          // in case of radio buttons we can select them by thier name instead of id.
          ':input[name="field_sf_block[0][settings][bso_status_alert_block][settings][site_status]"]' => ['value' => 'modified'],
        ],
      ],
    ];


    if($this->configuration['status_message']){
      $default = $this->configuration['status_message'];
    } else {
      $default = \Drupal::config('status_alert_message.settings')->get('status_alert_subtext_limited');
    }
    $form['bso_status_alert_block']['settings']['status_message']['limited'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status Message Subtext'),
      '#default_value' => $default,
      '#states' => [
        // Show this textfield only if the radio 'other' is selected above.
        'visible' => [
          // Don't mistake :input for the type of field or for a css selector --
          // it's a jQuery selector. 
          // You can always use :input or any other jQuery selector here, no matter 
          // whether your source is a select, radio or checkbox element.
          // in case of radio buttons we can select them by thier name instead of id.
          ':input[name="field_sf_block[0][settings][bso_status_alert_block][settings][site_status]"]' => ['value' => 'limited'],
        ],
      ],
    ];



    if($this->configuration['status_message']){
      $default = $this->configuration['status_message'];
    } else {
      $default = \Drupal::config('status_alert_message.settings')->get('status_alert_subtext_emergency');
    }
    $form['bso_status_alert_block']['settings']['status_message']['emergency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status Message Subtext'),
      '#default_value' => $default,
      '#states' => [
        // Show this textfield only if the radio 'other' is selected above.
        'visible' => [
          // Don't mistake :input for the type of field or for a css selector --
          // it's a jQuery selector. 
          // You can always use :input or any other jQuery selector here, no matter 
          // whether your source is a select, radio or checkbox element.
          // in case of radio buttons we can select them by thier name instead of id.
          ':input[name="field_sf_block[0][settings][bso_status_alert_block][settings][site_status]"]' => ['value' => 'emergency'],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $updated_block = $form_state->getValue('bso_status_alert_block');
    $this->setConfigurationValue('site_status', $updated_block['settings']['site_status']);
    $this->setConfigurationValue('status_message', $updated_block['settings']['status_message'][$updated_block['settings']['site_status']]);
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $status_message = \Drupal::config('status_alert_message.settings')->get('status_alert_message_'.$this->configuration["site_status"]);
    $status = '<h2 class="heading--ribbon-branding site-alert-'.$this->configuration["site_status"].'"><span><strong>'.$status_message.'</strong></span></h2>';
    if($this->configuration["status_message"]){
      $status .= '<p><em>'.$this->configuration["status_message"].'</em></p>';
    }
    return [
      '#markup' => $status
    ];
  }

}