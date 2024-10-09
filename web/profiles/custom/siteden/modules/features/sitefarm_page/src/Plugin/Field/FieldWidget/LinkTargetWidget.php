<?php

namespace Drupal\sitefarm_page\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;

/**
 * 22 September 2021 - Seth Hill, UCLA
 * This widget was written by Acquia as a means to give content administrators the ability to open external Related Links in a new window.
 * After discussion it was decided to avoid a custom FieldWidget and instead apply the behavior automatically with JavaScript.
 * See themes/custom/siteden_surface/js/1_pattern_lab/icon-link.js
 */

/**
 * Plugin implementation of the 'link_target_widget' widget.
 *
 * @FieldWidget(
 *   id = "link_target_widget",
 *   label = @Translation("Link with target"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class LinkTargetWidget extends LinkWidget {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'link_target' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $item = $this->getLinkItem($items, $delta);
    $options = $item->get('options')->getValue();

    // Add Target attribute in the link field.
    $element['options']['attributes']['target'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select a target'),
      '#options' => [
        '_blank' => 'New window (_blank)',
      ],
      '#default_value' => !empty($options['attributes']['target']) ? $options['attributes']['target'] : [],
      '#description' => $this->t('Please check this field if you want to open this link in a new window.'),
    ];
    return $element;
  }

  /**
   * Getting link items.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   Returning of field items.
   * @param string $delta
   *   Returning field delta with item.
   *
   * @return \Drupal\link\LinkItemInterface
   *   Returning link items inteface.
   */
  private function getLinkItem(FieldItemListInterface $items, $delta) {
    return $items[$delta];
  }

}
