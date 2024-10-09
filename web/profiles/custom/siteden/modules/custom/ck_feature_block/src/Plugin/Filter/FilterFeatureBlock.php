<?php

namespace Drupal\ck_feature_block\Plugin\Filter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;
use Drupal\Component\Utility\Html;
use Drupal\embed\DomHelperTrait;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filter to convert the Feature Block to correct html.
 *
 * @Filter(
 *   id = "filter_feature_block",
 *   title = @Translation("Feature Block Filter"),
 *   description = @Translation("Convert Feature Block to final HTML."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterFeatureBlock extends FilterBase implements ContainerFactoryPluginInterface {

  use DomHelperTrait;

  /**
   * The Renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * @var \DOMDocument
   */
  protected $dom;

  /**
   * Creates a UCCreditsBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Service to render markup from a render array.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, 'feature-block') !== FALSE) {
      $this->dom = Html::load($text);
      $xpath = new \DOMXPath($this->dom);

      /** @var \DOMElement $node */
      foreach ($xpath->query('//feature-block') as $node) {
        /** @var \DOMElement $node */
        $class = $node->getAttribute('class');

        $title = $this->getContentFromSlot('title', $node);
        $body = $this->getContentFromSlot('body', $node);

        $renderer = [
          '#theme' => 'feature_block',
          '#class' => $class,
          '#title' => $title,
          '#body' => $body,
        ];

        $output = $this->renderer->render($renderer);

        // Replace the original node with the new wrapped one.
        $this->replaceNodeContent($node, $output);
      }

      $result->setProcessedText(Html::serialize($this->dom));
    }

    return $result;
  }

  /**
   * Get the contents of a Slot in a DOMElement.
   *
   * @param string $slot
   *   The name of the slot to get content from.
   * @param \DOMElement $node
   *   The node that the slot will be found within.
   *
   * @return string
   *   The content of the slot.
   */
  protected function getContentFromSlot($slot, \DOMElement $node) {
    $content = '';

    // Find all the divs.
    foreach ($node->getElementsByTagName('div') as $child) {
      // Find which div has a slot matching the desired one.
      if ($child->getAttribute('slot') == $slot) {
        // Render all the elements of the slot to an html string.
        foreach ($child->childNodes as $item) {
          $content .= $this->dom->saveHTML($item);
        }

        break;
      }
    }

    return $content;
  }

}
