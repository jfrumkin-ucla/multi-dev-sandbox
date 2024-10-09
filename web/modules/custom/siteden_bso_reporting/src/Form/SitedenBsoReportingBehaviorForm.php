<?php

namespace Drupal\siteden_bso_reporting\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class SitedenBsoReportingBehaviorForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bso_reporting_behavior_form';
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['ucla_employee'] = [
      '#type' => 'radios',
      '#title' => $this->t('Are you a UCLA employee?'),
      '#options' => [
        '1' => $this->t('Yes'),
        '2' => $this->t('No')
      ]
    ];

    $form['ucla_affiliation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Please select your affiliation with UCLA'),
      '#options' => [
        '1' => $this->t('I am a UCLA Health Employee'),
        '2' => $this->t('I am a UCLA Campus Employee — <i>(Note: To access the Risk & Safety Solutions portal, first input your email in this format [UCLA Logon]@ucla.edu. You will then be prompted to input your UCLA Multi-Factor Authentication (MFA) login credentials).</i>')
      ],
      '#states' => [
        'visible' => [
          ':input[name="ucla_employee"]' => ['value' => '1'],
        ],
      ],
    ];

    $form['behavior_affiliation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select the UCLA affiliation of the person you are reporting about (do not select your affiliation).'),
      '#placeholder' => 'Enter favourite colour',
      '#options' => [
        '1' => $this->t('Student (undergraduate or graduate)'),
        '2' => $this->t('Campus Employee (staff, faculty, or volunteer)'),
        '5' => $this->t('UCLA Health Affiliate (employee, practitioner, patient, etc.)'),
        '3' => $this->t('UCLA Extension Student'),
        '4' => $this->t('Not sure or non-affiliate (no relationship to UCLA)')
      ],
      '#states' => [
        'visible' => [
          ':input[name="ucla_employee"]' => ['value' => '2'],
        ],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Go'),
      '#button_type' => 'primary',
      '#states' => [
        'visible' => [
          [
            ':input[name="ucla_affiliation"]' => [
              ['value' => '1'],
              'or',
              ['value' => '2'],
            ],
          ],
          'or',
          [
            ':input[name="behavior_affiliation"]' => [
              ['value' => '1'],
              'or',
              ['value' => '2'],
              'or',
              ['value' => '3'],
              'or',
              ['value' => '4'],
              'or',
              ['value' => '5']              
            ],
          ],
        ],
      ]
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('ucla_employee') == '1'){
      if ($form_state->getValue('ucla_affiliation') == '1'){
      \Drupal::messenger()->addMessage(t("ucla_affiliation = 1"));
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location:https://www.uclahealth.org/safety/workplace-violence-prevention");
        exit();      
      }
      if ($form_state->getValue('ucla_affiliation') == '2'){
      \Drupal::messenger()->addMessage(t("ucla_affiliation = 2"));
        header("HTTP/1.1 301 Moved Permanently");  
        header("Location:https://app.riskandsafety.com/poc/template/66732b3621a978b32b2c208c");
        exit();      
      }
    } else {
      if ($form_state->getValue('behavior_affiliation') == '1'){
      \Drupal::messenger()->addMessage(t("behavior_affiliation = 1"));
        header("HTTP/1.1 301 Moved Permanently");
        header("Location:https://ucla-advocate.symplicity.com/care_report/index.php/pid400995?");
        exit();      
      }
      if ($form_state->getValue('behavior_affiliation') == '2'){
      \Drupal::messenger()->addMessage(t("behavior_affiliation = 2"));
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location:https://ohr-ucla-gme-advocate.symplicity.com/care_report/index.php/pid553186?");
        exit();      
      }
      if ($form_state->getValue('behavior_affiliation') == '3'){
      \Drupal::messenger()->addMessage(t("behavior_affiliation = 3"));
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location:https://ohr-ucla-gme-advocate.symplicity.com/care_report/index.php/pid428292?");
        exit();      
      }
      if ($form_state->getValue('behavior_affiliation') == '4'){
      \Drupal::messenger()->addMessage(t("behavior_affiliation = 4"));
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location:https://ohr-ucla-gme-advocate.symplicity.com/care_report/index.php/pid559482?");
        exit();      
      }
      if ($form_state->getValue('behavior_affiliation') == '5'){
      \Drupal::messenger()->addMessage(t("behavior_affiliation = 5"));
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location:https://www.uclahealth.org/safety/workplace-violence-prevention");
        exit();      
      }
    }
  }
}
