<?php

namespace Drupal\siteden_embed_js\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EmbedJsForm.
 *
 * @package Drupal\siteden_embed_js\Form
 */
class EmbedJsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['siteden_embed_js.js_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'embed_js_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if (!$form_state->has('maximum_entries')) {
      $form_state->set('maximum_entries', 1);
    }

    $form['embed_js'] = [
      '#type' => 'table',
      '#tree' => TRUE,
      '#header' => [
        $this->t('Embed JS Link'),
        $this->t('Content Type'),
        $this->t('Embed JS Page'),
        $this->t('Asynchronous Javascript (async)'),
      ],
      '#prefix' => '<div id="embed-js-wrapper">',
      '#suffix' => '</div>',
    ];

    $embed_js_type = [
      'script' => $this->t('Javascript Resource'),
      'iframe' => $this->t('Iframe Resource'),
    ];

    $async_options = [
      'true' => $this->t('TRUE'),
      'false' => $this->t('FALSE'),
    ];

    // Obtain embed_js from configuration.
    if ($embed_js = $this->config('siteden_embed_js.js_settings')->get('embed_js')) {
      foreach ($embed_js as $key => $value) {
        $form['embed_js'][] = [
          'embed_js_link' => [
            '#type' => 'textfield',
            '#value' => str_replace('@', '.', $key),
          ],
          'embed_js_type' => [
            '#type' => 'select',
            '#options' => $embed_js_type,
            '#value' => $value['embed_js_type'] ?? 'script',
          ],
          'embed_js_page' => [
            '#type' => 'textarea',
            '#rows' => 10,
            '#description' => $this->t('Add one URL per line, wildcards are permitted. To add this javascript across all the pages, leave this field empty. For the front page, use the path &lt;front&gt;. Examples: <i>/admin/reports</i>, <i>/news/*</i>'),
            '#value' => is_array($value['embed_js_page']) ? implode("\n", $value['embed_js_page']) : '',
          ],
          'embed_js_async' => [
            '#type' => 'select',
            '#options' => $async_options,
            '#value' => $value['embed_js_async'] ?? 'false',
          ],
        ];
      }
    }

    // Fields for the new embed_js.
    for ($i = 0; $i < $form_state->get('maximum_entries'); $i++) {
      $form['embed_js'][] = [
        'embed_js_link' => [
          '#type' => 'textfield',
        ],
        'embed_js_type' => [
          '#type' => 'select',
          '#options' => $embed_js_type,
          '#default_value' => 'script',
        ],
        'embed_js_page' => [
          '#type' => 'textarea',
          '#description' => $this->t('Add one URL per line, wildcards are permitted. To add this javascript across all the pages, leave this field empty. For the front page, use the path &lt;front&gt;. Examples: <i>/admin/reports</i>, <i>/news/*</i>'),
          '#rows' => 10,
        ],
        'embed_js_async' => [
          '#type' => 'select',
          '#options' => $async_options,
          '#default_value' => 'false',
        ],
      ];
    }

    $form['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another'),
      '#submit' => ['::addAnotherSubmit'],
      '#ajax' => [
        'callback' => '::ajaxAddAnother',
        'wrapper' => 'embed-js-wrapper',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate javascript URL is in URL format or not.
    parent::validateForm($form, $form_state);
    $embed_js_configs = $form_state->getValue('embed_js');
    $invalid_url = FALSE;
    if ($embed_js_configs) {
      foreach ($embed_js_configs as $embed_js_config) {
        if (!empty($embed_js_config['embed_js_link']) && !(filter_var($embed_js_config['embed_js_link'], FILTER_VALIDATE_URL))) {
          $invalid_url = TRUE;
          break;
        }
      }
      if ($invalid_url) {
        $form_state->setErrorByName('embed_js',
         $this->t('All the URLs should be valid in Embed JS Link Field. Please validate you have given all the correct URLS'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $embed_js = [];
    $embed_js_config = $this->config('siteden_embed_js.js_settings');

    if ($form_inputs = $form_state->getValue('embed_js')) {
      foreach ($form_inputs as $input) {
        if (!empty($input['embed_js_link'])) {
          $input['embed_js_link'] = str_replace('.', '@', $input['embed_js_link']);
          $input['embed_js_page'] = !empty($input['embed_js_page']) ?
           preg_split('/[\n\r]+/', $input['embed_js_page']) : '';
          $embed_js[$input['embed_js_link']]['embed_js_page'] = $input['embed_js_page'];
          $embed_js[$input['embed_js_link']]['embed_js_type'] = $input['embed_js_type'];
          $embed_js[$input['embed_js_link']]['embed_js_async'] = $input['embed_js_async'];
        }
      }
    }
    $embed_js_config->set('embed_js', $embed_js);
    $embed_js_config->save();
  }

  /**
   * Ajax callback for adding another Embed JS Selection.
   *
   * @param array $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The new embed JS form part.
   */
  public function ajaxAddAnother(array $form, FormStateInterface $form_state) {
    return $form['embed_js'];
  }

  /**
   * Submit callback for adding a new Embed js field.
   */
  public function addAnotherSubmit(array $form, FormStateInterface $form_state) {
    $form_state->set('maximum_entries', $form_state->get('maximum_entries') + 1);
    $form_state->setRebuild(TRUE);
  }

}
