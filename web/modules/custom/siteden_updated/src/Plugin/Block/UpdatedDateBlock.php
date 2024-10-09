<?php

namespace Drupal\updated\Plugin\Block;

use Drupal\Component\Datetime\TimeInterface;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\TimeZoneFormHelper;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block that renders the last updated date value.
 *
 * @Block(
 *   id = "updated_date_block",
 *   admin_label = @Translation("Last Updated date block"),
 *   context_definitions = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Node"))
 *   }
 * )
 */
class UpdatedDateBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The date format entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $dateFormatStorage;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Creates a SystemBrandingBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Entity\EntityStorageInterface $date_format_storage
   *   The date format storage.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  final public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    DateFormatterInterface $date_formatter,
    EntityStorageInterface $date_format_storage,
    TimeInterface $time,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->dateFormatter = $date_formatter;
    $this->dateFormatStorage = $date_format_storage;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter'),
      $container->get('entity_type.manager')->getStorage('date_format'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label_display' => FALSE,
      'date_prefix' => $this->t('Last updated on'),
      'date_format' => 'custom',
      'custom_date_format' => 'F j, Y g:ia',
      'timezone' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Get node from the plugin context.
    $node = $this->getContextValue('node');

    // If display_updated is not checked, forbid access.
    /*$display_updated = $node->get('display_updated')->value;
    if (!$display_updated) {
      return AccessResult::forbidden('Forbidden by Update module')
        ->addCacheableDependency($node);
    }*/

    return parent::blockAccess($account);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['updated_block'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Date display settings'),
    ];

    $form['updated_block']['date_prefix'] = [
      '#title' => $this->t('Date Prefix'),
      '#type' => 'textfield',
      '#description' => $this->t('Text displayed immediately preceding the date.'),
      '#default_value' => $this->configuration['date_prefix'],
    ];

    $date_formats = [];
    foreach ($this->dateFormatStorage->loadMultiple() as $machine_name => $value) {
      $date_formats[$machine_name] = $this->t('@name format: @date', [
        '@name' => $value->label(),
        '@date' => $this->dateFormatter->format($this->time->getRequestTime(), $machine_name),
      ]);
    }
    $date_formats['custom'] = $this->t('Custom');

    $form['updated_block']['formatter_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Settings for the timestamp <em>Default</em> field formatter.'),
    ];

    $form['updated_block']['formatter_settings']['date_format'] = [
      '#type' => 'select',
      '#title' => $this->t('Date format'),
      '#options' => $date_formats,
      '#default_value' => $this->configuration['date_format'],
    ];

    $form['updated_block']['formatter_settings']['custom_date_format'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom date format'),
      '#description' => $this->t('See <a href="https://www.php.net/manual/datetime.format.php#refsect1-datetime.format-parameters" target="_blank">the documentation for PHP date formats</a>.'),
      '#default_value' => $this->configuration['custom_date_format'] ?: '',
    ];

    $form['updated_block']['formatter_settings']['custom_date_format']['#states']['visible'][] = [
      ':input[name="settings[updated_block][formatter_settings][date_format]"]' => ['value' => 'custom'],
    ];

    $timezones = TimeZoneFormHelper::getOptionsListByRegion();
    $form['updated_block']['formatter_settings']['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Time zone'),
      '#options' => ['' => $this->t('- Default site/user time zone -')] + $timezones,
      '#default_value' => $this->configuration['timezone'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $updated_block = $form_state->getValue('updated_block');
    $this->setConfigurationValue('date_prefix', strip_tags($updated_block['date_prefix']));
    $this->setConfigurationValue('date_format', $updated_block['formatter_settings']['date_format']);
    $this->setConfigurationValue('custom_date_format', $updated_block['formatter_settings']['custom_date_format']);
    $this->setConfigurationValue('timezone', $updated_block['formatter_settings']['timezone']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

  $view_blocks=[];
  foreach(\Drupal::entityTypeManager()->getStorage('view')->loadMultiple() as $view){
    $view_displays = $view->get('display');
    foreach($view_displays as $vd_key => $vd_value){
      if($vd_value['display_plugin']=='block'){
        $view_blocks[$view->id()."&d=".$vd_value['id']] = $view->label()." - ".$vd_value['display_title'];     
      }
    }
  }


    $build = [];
    // Get node from the plugin context.
    $node = $this->getContextValue('node');
    $attached_views = $node->get('display_updated')->getValue();
    $updated_times = [];
    if($attached_views){
      foreach($attached_views as $attached_view){
        $attached_view_array = explode('&d=', $attached_view['value']);
        $view = views_get_view_result($attached_view_array[0],$attached_view_array[1]);
        foreach($view as $vkey => $vrow){
          $updated_times[$vrow->_entity->id()] = $vrow->_entity->getCreatedTime();
          if(isset($vrow->_entity->get('changed')->getValue()[0]['value'])){
            if($vrow->_entity->get('changed')->getValue()[0]['value'] > $vrow->_entity->getCreatedTime()){
              $updated_times[$vrow->_entity->id()] = $vrow->_entity->get('changed')->getValue()[0]['value'];
            }
          }
        }
      }
    }

    $updated_times['updated'] = $node->get('changed')->getValue()[0]['value'];
    $updated_times['changed'] = $node->getCreatedTime();
    // Get configuration values.
    $configuration = $this->getConfiguration();
    $date_format = $configuration['date_format'];
    $custom_date_format = $configuration['custom_date_format'];
    $timezone = $configuration['timezone'];


    $most_recent_update = max($updated_times);
    $most_recent_update_key = array_search ($most_recent_update, $updated_times);
    $most_recent_update_date = date($configuration['custom_date_format'], $most_recent_update);
    $most_recent_update_date = str_replace("am", ' a.m.', $most_recent_update_date);
    $most_recent_update_date = str_replace("pm", ' p.m.', $most_recent_update_date);
    return [
      '#markup' => "<span class='most-recent-update' update_source=".$most_recent_update_key.">".$configuration['date_prefix']." ".$most_recent_update_date."</span>",
    ];

 //   return $build;
  }


  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
