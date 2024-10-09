<?php

namespace Drupal\sitefarm_core\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
/**
 * Class HtmlDecode.
 *
 * @package Drupal\sitefarm_core\Twig
 */
class HtmlDecode extends AbstractExtension {

  /**
   * {@inheritDoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('html_decode', $this->htmlDecode(...)),
    ];
  }

  /**
   * Decodes HTML entities.
   *
   * @param string $value
   *   The value to decode html entities from.
   *
   * @return string
   *   The decoded value.
   */
  public function htmlDecode(string $value) {
    return html_entity_decode($value);
  }

}
