<?php

namespace Drupal\ck_columns\Plugin\Filter;

use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;
use Drupal\Component\Utility\Html;
use Drupal\embed\DomHelperTrait;

/**
 * Filter to convert the Layout Columns to correct html.
 *
 * @Filter(
 *   id = "filter_columns",
 *   title = @Translation("Layout Columns"),
 *   description = @Translation("Convert Layout Columns to final HTML."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterColumns extends FilterBase {

  use DomHelperTrait;

  /**
   * @var \DOMDocument
   */
  protected $dom;

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, 'layout-columns') !== FALSE) {
      $this->dom = Html::load($text);
      $xpath = new \DOMXPath($this->dom);
      $selector = '//layout-columns';

      /** @var \DOMNodeList $components_found */
      foreach ($xpath->query($selector) as $index => $node) {
        // Fetch a fresh node so that children are rendered correctly.
        $node = $xpath->query($selector)[$index];
        if ($node) {
          $this->convertComponent($node);
        }
      }

      $result->setProcessedText(Html::serialize($this->dom));
    }

    return $result;
  }

  /**
   * Convert the component into the new format with a Twig template.
   *
   * @param \DOMElement $node
   *   A DOMElement object.
   */
  protected function convertComponent(\DOMElement &$node) {
    // Move the custom class to a normal class attribute.
    $class = $node->getAttribute('custom-class');
    $node->removeAttribute('custom-class');
    if ($class) {
      // Check to see if a class already exists.
      $current_classes = $node->getAttribute('class');
      if ($current_classes) {
        $class = $current_classes . ' ' . $class;
      }
      $node->setAttribute('class', $class);
    }

    // Remove extra slots not being used.
    $columns = $node->getAttribute('columns');

    if ($columns && $columns < 4) {
      $this->removeSlot('column4', $node);
    }

    if ($columns && $columns < 3) {
      $this->removeSlot('column3', $node);
    }

    if ($columns && $columns < 2) {
      $this->removeSlot('column2', $node);
    }

    $new_node = $this->dom->saveHTML($node);

    // Replace the original node with the new one.
    $this->replaceNodeContent($node, $new_node);
  }

  /**
   * Get the contents of a Slot in a DOMElement.
   *
   * @param string $slot
   *   The name of the slot to get content from.
   * @param \DOMElement $node
   *   The node that the slot will be found within.
   */
  protected function removeSlot($slot, \DOMElement &$node) {
    // Find all the divs.
    foreach ($node->childNodes as $child) {
      if (!$child instanceof \DOMElement) {
        continue;
      }

      // Find which div has a slot matching the desired one.
      if ($child->getAttribute('slot') == $slot) {
        $child->parentNode->removeChild($child);
        break;
      }
    }
  }

}
