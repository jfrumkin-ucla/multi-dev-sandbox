<?php

namespace Drupal\linkit\Plugin\Linkit\Matcher;

use Drupal\Component\Utility\Html;
use Drupal\linkit\MatcherBase;
use Drupal\linkit\Suggestion\DescriptionSuggestion;
use Drupal\linkit\Suggestion\SuggestionCollection;

/**
 * Provides specific linkit matchers for external links missing a protocol.
 *
 * @Matcher(
 *   id = "external",
 *   label = @Translation("External"),
 * )
 */
class ExternalMatcher extends MatcherBase {

  /**
   * {@inheritdoc}
   */
  public function execute($string) {
    $suggestions = new SuggestionCollection();
    if (self::canBeUrl($string)) {
      $suggestion = new DescriptionSuggestion();
      $suggestion->setLabel($this->t('External URL https://@url', ['@url' => $string]))
        ->setPath('https://' . Html::escape($string))
        ->setGroup($this->t('External URL'))
        ->setDescription($this->t('Adds https:// to URL.'));

      $suggestions->addSuggestion($suggestion);
    }
    if (self::canBeUrlHttp($string)) {
      $suggestion = new DescriptionSuggestion();
      $suggestion->setLabel($this->t('External URL http://@url', ['@url' => $string]))
        ->setPath('http://' . Html::escape($string))
        ->setGroup($this->t('External URL'))
        ->setDescription($this->t('Adds http:// to URL.'));

      $suggestions->addSuggestion($suggestion);
    }
    if (self::canBeEmail($string)) {
      $suggestion = new DescriptionSuggestion();
      $suggestion->setLabel($this->t('Email address @url', ['@url' => $string]))
        ->setPath('mailto:' . Html::escape($string))
        ->setGroup($this->t('External URL'))
        ->setDescription($this->t('Adds mailto: Linkit'));

      $suggestions->addSuggestion($suggestion);
    }    
    return $suggestions;
  }

  /**
   * Check whether the string is eligible for external URL protocol.
   *
   * @var $string string
   *   The user-entered string.
   *
   * @return bool
   *   Whether or not it is URL-eligible.
   */
  public static function canBeUrl($string) {
    // Do not suggest external links if this already looks like a URL.
    if (str_starts_with($string, 'http') || str_contains($string, '://') || str_contains($string, '@')) {
      return FALSE;
    }
    $url = "https://" . $string;
    // If this would be a URL if https:// were added...
    if (filter_var($url, FILTER_VALIDATE_URL) && strpos($url, '.') !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check whether the string is eligible for external URL protocol unsecure.
   *
   * @var $string string
   *   The user-entered string.
   *
   * @return bool
   *   Whether or not it is URL-eligible.
   */
  public static function canBeUrlHttp($string) {
    // Do not suggest external links if this already looks like a URL.
    if (str_starts_with($string, 'http') || str_contains($string, '://') || str_contains($string, '@')) {
      return FALSE;
    }
    $url = "https://" . $string;
    // If this would be a URL if https:// were added...
    if (filter_var($url, FILTER_VALIDATE_URL) && strpos($url, '.') !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check whether the string is eligible for external URL protocol unsecure.
   *
   * @var $string string
   *   The user-entered string.
   *
   * @return bool
   *   Whether or not it is URL-eligible.
   */
  public static function canBeEmail($string) {
    // Do not suggest external links if this already looks like a URL.
    if (str_contains($string, 'mailto:')) {
      return FALSE;
    }
    $url = "mailto:" . $string;
    // If this would be a URL if https:// were added...
    if (strpos($url, '@') !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

}
