<?php

namespace Drupal\sitefarm_wysiwyg\Plugin\Filter;

use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;
use Drupal\Component\Utility\Html;
use Drupal\embed\DomHelperTrait;

/**
 * Filter to convert the Tables into Responsive Tables.
 *
 * @Filter(
 *   id = "filter_responsive_tables",
 *   title = @Translation("Responsive Tables"),
 *   description = @Translation("Wrap Tables with a scrollable wrapper."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterResponsiveTables extends FilterBase {

  use DomHelperTrait;

  /**
   * @var \DOMDocument
   */
  protected $dom;

  /**
   * @var \DOMXPath
   */
  protected $xpath;

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, '<table') === FALSE) {
      return $result;
    }

    $this->dom = Html::load($text);
    $this->xpath = new \DOMXPath($this->dom);
    // Only get tables that are not nested in another table.
    $selector = '//table[not(ancestor::table)]';

    /** @var \DOMNodeList $components_found */
    foreach ($this->xpath->query($selector) as $index => $node) {
      // Fetch a fresh node so that children are rendered correctly.
      $node = $this->xpath->query($selector)[$index];
      if ($node) {
        $this->convertComponent($node);
      }
    }

    $result->setProcessedText(Html::serialize($this->dom));

    return $result;
  }

  /**
   * Wrap the table with the responsive html wrapper.
   *
   * @param \DOMElement $node
   *   A DOMElement object.
   */
  protected function convertComponent(\DOMElement &$node) {
    // Use the <caption> for the aria-label if available.
    $caption = 'Scrollable Table';
    $captionElement = $this->xpath->query('./caption', $node);
    if ($captionElement && $captionElement[0]) {
      $caption = htmlspecialchars($captionElement[0]->nodeValue);
    }

    $nodeHtml = $this->dom->saveHTML($node);

    $output = <<<EOT
<div role="region" aria-label="{$caption}" tabindex="0" class="responsive-table">{$nodeHtml}</div>
EOT;

    // Replace the original node with the new wrapped one.
    $this->replaceNodeContent($node, $output);
  }

}
