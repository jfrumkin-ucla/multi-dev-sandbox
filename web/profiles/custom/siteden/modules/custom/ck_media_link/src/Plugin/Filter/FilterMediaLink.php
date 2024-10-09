<?php

namespace Drupal\ck_media_link\Plugin\Filter;

use Drupal\Core\Url;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\redirect\RedirectRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Path\PathValidator;
use Drupal\path_alias\AliasManager;
use Drupal\Component\Utility\Html;
use Drupal\embed\DomHelperTrait;
use Drupal\Core\Render\Renderer;

/**
 * Filter to convert a Media Link widget into correct HTML.
 *
 * @Filter(
 *   id = "filter_media_link",
 *   title = @Translation("Media Link Filter"),
 *   description = @Translation("Convert Media Link component to final HTML."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterMediaLink extends FilterBase implements ContainerFactoryPluginInterface {

  use DomHelperTrait;

  /**
   * @var \Drupal\Core\Path\PathValidator
   */
  protected $pathValidator;

  /**
   * @var \Drupal\path_alias\AliasManager
   */
  protected $aliasManager;

  /**
   * @var \Drupal\redirect\RedirectRepository
   */
  protected $redirectRepository;

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
   * @param \Drupal\Core\Path\PathValidator $pathValidator
   *   Path Validator service.
   * @param \Drupal\path_alias\AliasManager $aliasManager
   *   Alias Manager service.
   * @param \Drupal\redirect\RedirectRepository $redirectRepository
   *   Redirect service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Service to render markup from a render array.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PathValidator $pathValidator, AliasManager $aliasManager, RedirectRepository $redirectRepository, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->pathValidator = $pathValidator;
    $this->aliasManager = $aliasManager;
    $this->redirectRepository = $redirectRepository;
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
      $container->get('path.validator'),
      $container->get('path_alias.manager'),
      $container->get('redirect.repository'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (strpos($text, 'media-link') !== FALSE) {
      $this->dom = Html::load($text);
      $xpath = new \DOMXPath($this->dom);

      foreach ($xpath->query('//media-link') as $node) {
        /** @var \DOMElement $node */
        $url = $node->getAttribute('url');
        $url = $this->formatPath($url);

        $image = $this->getContentFromSlot('image', $node);
        $title = $this->getContentFromSlot('title', $node);
        $content = $this->getContentFromSlot('content', $node);

        $renderer = [
          '#theme' => 'media_link',
          '#url' => $url,
          '#image' => $image,
          '#title' => $title,
          '#content' => $content,
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
   * Check that a path is valid and format it correctly.
   *
   * @param string $path
   *   The url whether internal or external to check against.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   The processed path.
   */
  public function formatPath($path) {
    $path = trim($path);

    // Check to see if this path has a possible redirect.
    if ($path_redirect = $this->newRedirectPath($path)) {
      $path = $path_redirect;
    }

    // Add a preceding slash to internal url.
    if (!preg_match('/^http/', $path) && !preg_match('/^\//', $path)) {
      $path = '/' . $path;
    }

    // If this is not valid internal path such as node/1 or external url.
    if (!$this->pathValidator->isValid($path)) {
      // If this is not a valid internal path alias.
      $alias = $this->aliasManager->getAliasByPath($path);
      if ($path == $alias) {
        // Only add one slash since we already added one earlier.
        return 'http:/' . $path;
      }
    }

    return $path;
  }

  /**
   * Get a path redirect.
   *
   * @param string $path
   *   A url path.
   *
   * @return bool|\Drupal\Core\GeneratedUrl|string
   *   An internal Drupal path.
   */
  protected function newRedirectPath($path) {
    // Remove any possible slash at the beginning since redirects do no look for
    // the slash.
    $path = ltrim($path, '/');

    if ($redirects = $this->redirectRepository->findBySourcePath($path)) {
      /** @var \Drupal\redirect\Entity\Redirect $redirect */
      if ($redirect = reset($redirects)) {
        $uri = $redirect->getRedirect()['uri'];
        // Return the new redirect path.
        return $this->urlFromUri($uri);
      }
    }

    return FALSE;
  }

  /**
   * This is a wrapper method for easier unit testing.
   *
   * @param string $uri
   *   A Drupal uri.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   The internal path.
   */
  protected function urlFromUri($uri) {
    return Url::fromUri($uri)->toString();
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
