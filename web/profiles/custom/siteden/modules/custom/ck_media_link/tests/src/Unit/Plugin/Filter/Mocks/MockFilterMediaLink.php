<?php

namespace Drupal\Tests\ck_media_link\Unit\Plugin\Filter\Mocks;

use Drupal\ck_media_link\Plugin\Filter\FilterMediaLink;

/**
 * Overrides methods which have static functions.
 */
class MockFilterMediaLink extends FilterMediaLink {

  /**
   * Get the testable path returned from uri since it uses a static method.
   *
   * @param string $uri
   *   A Drupal uri.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   The internal path.
   */
  protected function urlFromUri($uri) {
    if ($uri == 'internal:/node/1') {
      return '/new';
    }
    else {
      return FALSE;
    }
  }

}
