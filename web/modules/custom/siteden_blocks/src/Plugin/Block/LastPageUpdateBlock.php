<?php

namespace Drupal\siteden_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Site Last Updated' Block.
 */
#[Block(
  id: "last_page_update_block",
  admin_label: new TranslatableMarkup("Page Content Last Updated"),
  category: new TranslatableMarkup("SiteDen")
)]
class LastPageUpdateBlock extends BlockBase implements ContainerFactoryPluginInterface, CacheableDependencyInterface {


  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new RutaActualInfoBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $routeName = $this->routeMatch->getRouteName();
    $routeParameters = $this->routeMatch->getParameters()->all();
    $routePath = $this->routeMatch->getRouteObject()->getPath();
    $routeMethods = $this->routeMatch->getRouteObject()->getMethods();

    $info = [
      '#theme' => 'item_list',
      '#items' => [
        $this->t('Route name: @route_name', ['@route_name' => $routeName]),
        $this->t('Route parameters: @parameters', ['@parameters' => json_encode($routeParameters)]),
        $this->t('Route path: @path', ['@path' => json_encode($routePath)]),
        $this->t('Route methods: @methods', ['@methods' => json_encode($routeMethods)]),
      ],
      '#title' => $this->t('Current Route Information'),
    ];

    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // No cache context required.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // No specific cache tags required.
    return [];
  }

}