<?php

namespace Drupal\sitefarm_photo_gallery\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Cache\Cache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\Renderer;

/**
 * Provides a 'SlideshowGalleryBlock' block.
 *
 * @Block(
 *  id = "slideshow_gallery_block",
 *  admin_label = @Translation("Slideshow Photo Gallery"),
 * )
 */
class SlideshowGalleryBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Instance of the Entity Type Manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Instance of the Renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Creates a SlideshowGalleryBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity Type Manager service Interface.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The Renderer service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entityTypeManager, Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entityTypeManager;
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
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'show_title' => TRUE,
      'slider_nav' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Fetch the default node.
    $node = FALSE;
    if (isset($this->configuration['gallery'])) {
      $node = $this->entityTypeManager->getStorage('node')->load($this->configuration['gallery']);
    }

    $form['display'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Slideshow Settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['display']['gallery'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Gallery Title'),
      '#target_type' => 'node',
      '#selection_settings' => [
        'target_bundles' => ['sf_photo_gallery'],
      ],
      '#description' => $this->t('Select the gallery to be displayed by typing the title of the gallery. As you type, suggestions will appear for you to select a gallery.'),
      '#default_value' => $node,
      '#required' => TRUE,
    ];
    $form['display']['show_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show the Gallery Title on the first slide.'),
      '#default_value' => $this->configuration['show_title'],
    ];
    $form['display']['slider_nav'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Slider Navigation Thumbnails'),
      '#description' => $this->t('Display a list of thumbnail images for navigation the gallery.'),
      '#default_value' => $this->configuration['slider_nav'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    foreach ($values['display'] as $key => $value) {
      $this->setConfigurationValue($key, $form_state->getValue(['display', $key]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the config for this block.
    $config = $this->getConfiguration();

    // Fetch the photo gallery node.
    $nid = $config['gallery'];
    /** @var \Drupal\node\Entity\Node $node */
    $node = $this->entityTypeManager->getStorage('node')->load($nid);

    // Return empty if there is no node attached (something went wrong).
    if (!$node) {
      return [];
    }

    $title = $node->getTitle();

    // Load individual referenced media items from the field to use as slides.
    $field = $node->field_sf_m_photos;
    $slides = $field->referencedEntities();

    // Get the image styles we will want to apply to the images.
    /** @var \Drupal\image\Entity\ImageStyle $style_full */
    $style_full = $this->entityTypeManager->getStorage('image_style')->load('sf_slideshow_full');
    /** @var \Drupal\image\Entity\ImageStyle $style_thumb */
    $style_thumb = $this->entityTypeManager->getStorage('image_style')->load('sf_slideshow_thumbnail');

    $slides_main = [];
    $slides_nav = [];

    foreach ($slides as $media) {
      /** @var \Drupal\media\Entity\Media $media */
      $media_reference = $media->field_media_image;
      /** @var \Drupal\file\Plugin\Field\FieldType\FileFieldItemList $media_reference */
      $props = $media_reference->getValue();
      $media_files = $media_reference->referencedEntities();
      $file = $media_files[0];
      /** @var \Drupal\file\Entity\File $file */
      $uri = $file->getFileUri();

      // Provide media caption if it exists.
      $caption = $media->field_sf_media_caption->getValue();

      // Create the main slides from the File.
      $image_build = [
        '#theme' => 'image_style',
        '#width' => $props[0]['width'],
        '#height' => $props[0]['height'],
        '#style_name' => 'sf_slideshow_full',
        '#alt' => $props[0]['alt'],
        '#uri' => $uri,
      ];

      // Add the file entity to the cache dependencies.
      // This will clear our cache when this entity updates.
      $this->renderer->addCacheableDependency($image_build, $file);

      $slides_main[] = [
        'image' => $image_build,
        'src' => $style_full->buildUrl($uri),
        'alt' => $props[0]['alt'],
        'caption' => $caption[0]['value'] ?? NULL,
      ];

      // Create the slider nav thumbnails.
      if ($config['slider_nav']) {
        $thumb_build = $image_build;
        $thumb_build['#style_name'] = 'sf_slideshow_thumbnail';

        $this->renderer->addCacheableDependency($thumb_build, $file);

        $slides_nav[] = [
          'image' => $thumb_build,
          'src' => $style_thumb->buildUrl($uri),
          'alt' => $props[0]['alt'],
        ];
      }
    }

    // Create the build array with asset libraries already attached.
    $build = [
      '#attached' => [
        'library' => [
          'sitefarm_photo_gallery/sitefarm_photo_gallery',
          'sitefarm_photo_gallery/sitefarm_photo_gallery.slick',
          'sitefarm_photo_gallery/sitefarm_photo_gallery.slick_theme',
        ],
      ],
    ];

    // Set up the Main Slide array.
    $build['slideshow_main'] = [
      '#theme' => 'slideshow_main',
      '#show_title' => $config['show_title'],
      '#title_full' => $title,
      '#slides' => $slides_main,
    ];

    // Set up the Navigation Slide array.
    if (!empty($slides_nav)) {
      $build['slideshow_nav'] = [
        '#theme' => 'slideshow_nav',
        '#slides' => $slides_nav,
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // Rebuild the block when the node changes.
    if (isset($this->configuration['gallery'])) {
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $this->configuration['gallery']]);
    }
    else {
      // Return default tags instead.
      return parent::getCacheTags();
    }
  }

}
