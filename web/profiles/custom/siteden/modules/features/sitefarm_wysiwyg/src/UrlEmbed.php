<?php

namespace Drupal\sitefarm_wysiwyg;

use Drupal\url_embed\Plugin\EmbedType\Url;

/**
 * Override the Url class to add a custom image.
 *
 * @EmbedType(
 *   id = "url",
 *   label = @Translation("URL")
 * )
 */
class UrlEmbed extends Url {

  /**
   * {@inheritdoc}
   */
  public function getDefaultIconUrl() {
    // Replace the default image with a custom one.
    // Play Cloud by Santiago Arias from the Noun Project
    // https://thenounproject.com/Sa_arias/uploads/?i=249073
    return \Drupal::service('file_url_generator')->generateAbsoluteString(\Drupal::service('extension.list.module')->getPath('sitefarm_wysiwyg') . '/images/url_embed.png');
  }

}
