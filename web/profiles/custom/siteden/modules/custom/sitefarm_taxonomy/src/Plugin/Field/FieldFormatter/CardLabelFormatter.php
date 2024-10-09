<?php

namespace Drupal\sitefarm_taxonomy\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'card_label' formatter.
 *
 * @FieldFormatter(
 *   id = "card_label",
 *   label = @Translation("UCLA Label"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class CardLabelFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'card_link' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements['card_link'] = [
      '#title' => t('Link label to the referenced entity'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('card_link'),
    ];

    return $elements;

  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->getSetting('card_link') ? t('Link to the referenced entity') : t('No link');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = [];
    $output_as_link = $this->getSetting('card_link');

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      // Set the default brand color to label--primary.
      $element_brand_color = 'label--primary';
      // If the link is to be displayed and the entity has a uri, display a
      // link.
      if ($output_as_link && !$entity->isNew()) {
        $alias_manager = \Drupal::service('path_alias.manager');
        $term_alias = $alias_manager->getAliasByPath("/taxonomy/term/" . $entity->id());
      }

      if ($output_as_link && !$entity->isNew()) {
        $elements[$delta] = [
          '#type' => 'link',
          '#term_alias' => $term_alias,
        ];
      }
      $elements[$delta]['#title'] = $entity->label();
      // Check Whether element has configured brand color field or not.
      if ($entity->hasField('field_sf_brand_color')) {
        $element_brand_color = $entity->field_sf_brand_color->getValue()[0]['value'];
      }

      $elements[$delta]['#brand_color'] = $element_brand_color;
      $elements[$delta]['#theme'] = 'card_link_formatter';
      $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
    }

    return $elements;
  }

}
