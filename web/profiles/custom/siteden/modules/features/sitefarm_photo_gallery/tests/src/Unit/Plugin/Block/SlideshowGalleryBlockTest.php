<?php

namespace Drupal\Tests\sitefarm_photo_gallery\Unit\Plugin\Block;

use Drupal\Tests\UnitTestCase;
use Drupal\sitefarm_photo_gallery\Plugin\Block\SlideshowGalleryBlock;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Render\Renderer;
use Drupal\image\Entity\ImageStyle;
use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\Core\Field\EntityReferenceFieldItemList;

/**
 * @coversDefaultClass \Drupal\sitefarm_photo_gallery\Plugin\Block\SlideshowGalleryBlock
 * @group sitefarm_photo_gallery
 */
class SlideshowGalleryBlockTest extends UnitTestCase {

  /**
   * The mocked config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $configFactory;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Form State Mock stub.
   *
   * @var \Drupal\Core\Form\FormStateInterface
   */
  protected $formState;

  /**
   * Default config for the block plugin.
   *
   * @var array
   */
  protected $pluginConfig = [
    'show_title' => TRUE,
    'slider_nav' => FALSE,
  ];

  /**
   * @var \Drupal\sitefarm_photo_gallery\Plugin\Block\SlideshowGalleryBlock
   */
  protected $plugin;

  /**
   * Create the setup for constants and configFactory stub.
   */
  protected function setUp(): void {
    parent::setUp();

    // Stub config.
    $this->configFactory = $this->getConfigFactoryStub([
      'gallery' => [],
    ]);

    // Stub the Entity Type Manager.
    $this->entityTypeManager = $this->prophesize(EntityTypeManagerInterface::CLASS);

    // Stub the Renderer.
    $this->renderer = $this->prophesize(Renderer::CLASS);

    // Stub form_state.
    $this->formState = $this->createMock(FormStateInterface::CLASS);

    $plugin_id = 'sitefarm_photo_gallery_block';
    $plugin_definition['provider'] = 'sitefarm_photo_gallery';

    $this->plugin = new SlideshowGalleryBlock(
      $this->pluginConfig,
      $plugin_id,
      $plugin_definition,
      $this->configFactory,
      $this->entityTypeManager->reveal(),
      $this->renderer->reveal()
    );

    // Create a translation stub for the t() method.
    $translator = $this->getStringTranslationStub();
    $this->plugin->setStringTranslation($translator);
  }

  /**
   * Tests the create method.
   */
  public function testCreate() {
    $plugin_id = 'sitefarm_photo_gallery_block';
    $plugin_definition['provider'] = 'sitefarm_photo_gallery';

    $container = $this->prophesize(ContainerInterface::CLASS);
    $container->get('config.factory')->willReturn($this->configFactory);
    $container->get('entity_type.manager')
      ->willReturn($this->entityTypeManager);
    $container->get('renderer')->willReturn($this->renderer);

    $instance = SlideshowGalleryBlock::create($container->reveal(), $this->pluginConfig, $plugin_id, $plugin_definition);
    $this->assertInstanceOf('Drupal\sitefarm_photo_gallery\Plugin\Block\SlideshowGalleryBlock', $instance);
  }

  /**
   * Tests the blockSubmit method.
   */
  public function testBlockSubmit() {
    $options = [
      'display' => $this->pluginConfig,
    ];

    $this->formState->expects($this->any())
      ->method('getValues')
      ->willReturn($options);
    $this->formState->expects($this->any())
      ->method('getValue')
      ->willReturnCallback(function ($array) use ($options) {
        return $options[$array[0]][$array[1]];
      });

    $expectedConfig = $this->pluginConfig + [
      'id' => 'sitefarm_photo_gallery_block',
      'label' => '',
      'provider' => 'sitefarm_photo_gallery',
      'label_display' => 'visible',
    ];

    $form = [];
    $this->plugin->blockSubmit($form, $this->formState);
    $this->assertEquals($expectedConfig, $this->plugin->getConfiguration());
  }

  /**
   * Tests the build method.
   */
  public function testBuild() {
    $config = array_merge($this->pluginConfig, [
      'gallery' => 1,
      'slider_nav' => 1,
    ]);
    $this->plugin->setConfiguration($config);

    $field_props = [
      [
        'target_id' => '1',
        'alt' => 'alt text',
        'title' => '',
        'width' => '720',
        'height' => '480',
      ],
    ];

    $file = $this->prophesize(File::CLASS);
    $file->getFileUri()->willReturn('fileuri');

    $media = $this->prophesize(Media::CLASS);
    $media_rendered = $media->reveal();

    $media_files = [
      $file->reveal(),
    ];

    $media_reference = $this->prophesize(FileFieldItemList::CLASS);
    $media_reference->getValue()->willReturn($field_props);
    $media_reference->referencedEntities()->willReturn($media_files);

    $caption_field = $this->prophesize(FileFieldItemList::CLASS);
    $caption_field->getValue()->willReturn([['value' => 'my caption']]);

    // Use reflection to alter the protected $media->fieldDefinitions.
    $reflectionObject = new \ReflectionObject($media_rendered);
    $property = $reflectionObject->getProperty('fieldDefinitions');
    $property->setAccessible(TRUE);
    $property->setValue($media_rendered, []);
    // Use reflection to alter the protected $media->values.
    $property = $reflectionObject->getProperty('values');
    $property->setAccessible(TRUE);
    $property->setValue($media_rendered, [
      'field_media_image' => $media_reference->reveal(),
      'field_sf_media_caption' => $caption_field->reveal(),
    ]);

    $slides = [
      $media->reveal(),
    ];

    $field = $this->prophesize(EntityReferenceFieldItemList::CLASS);
    $field->referencedEntities()->willReturn($slides);

    $node = $this->prophesize(Node::CLASS);
    $node->getTitle()->willReturn('Test Title');
    $node_rendered = $node->reveal();

    // Use reflection to alter the protected $node->fieldDefinitions.
    $reflectionObject = new \ReflectionObject($node_rendered);
    $property = $reflectionObject->getProperty('fieldDefinitions');
    $property->setAccessible(TRUE);
    $property->setValue($node_rendered, []);
    // Use reflection to alter the protected $node->values.
    $property = $reflectionObject->getProperty('values');
    $property->setAccessible(TRUE);
    $property->setValue($node_rendered, ['field_sf_m_photos' => $field->reveal()]);

    $image_style = $this->prophesize(ImageStyle::CLASS);
    $image_style->buildUrl('fileuri')->willReturn('image_src');
    $image_style_thumb = $this->prophesize(ImageStyle::CLASS);
    $image_style_thumb->buildUrl('fileuri')->willReturn('thumb_src');

    $entityStorage = $this->prophesize(EntityStorageInterface::CLASS);
    $entityStorage->load(1)->willReturn($node_rendered);
    $entityStorage->load('sf_slideshow_full')
      ->willReturn($image_style->reveal());
    $entityStorage->load('sf_slideshow_thumbnail')
      ->willReturn($image_style_thumb->reveal());

    $this->entityTypeManager->getStorage('node')
      ->willReturn($entityStorage->reveal());
    $this->entityTypeManager->getStorage('image_style')
      ->willReturn($entityStorage->reveal());

    $expectedBuild = [
      '#attached' => [
        'library' => [
          'sitefarm_photo_gallery/sitefarm_photo_gallery',
          'sitefarm_photo_gallery/sitefarm_photo_gallery.slick',
          'sitefarm_photo_gallery/sitefarm_photo_gallery.slick_theme',
        ],
      ],
      'slideshow_main' => [
        '#theme' => 'slideshow_main',
        '#show_title' => TRUE,
        '#title_full' => 'Test Title',
        '#slides' => [
          [
            'image' => [
              '#theme' => 'image_style',
              '#width' => '720',
              '#height' => '480',
              '#style_name' => 'sf_slideshow_full',
              '#alt' => 'alt text',
              '#uri' => 'fileuri',
            ],
            'src' => 'image_src',
            'alt' => 'alt text',
            'caption' => 'my caption',
          ],
        ],
      ],
      'slideshow_nav' => [
        '#theme' => 'slideshow_nav',
        '#slides' => [
          [
            'image' => [
              '#theme' => 'image_style',
              '#width' => '720',
              '#height' => '480',
              '#style_name' => 'sf_slideshow_thumbnail',
              '#alt' => 'alt text',
              '#uri' => 'fileuri',
            ],
            'src' => 'thumb_src',
            'alt' => 'alt text',
          ],
        ],
      ],
    ];

    $return = $this->plugin->build();
    $this->assertEquals($expectedBuild, $return);
  }

  /**
   * Tests the build method does not have a valid node.
   *
   * @covers ::build()
   */
  public function testBuildDoesNotHaveValidNode() {
    $this->plugin->setConfiguration($this->pluginConfig + ['gallery' => 1]);

    $entityStorage = $this->prophesize(EntityStorageInterface::CLASS);
    $entityStorage->load(1)->willReturn(NULL);

    $this->entityTypeManager->getStorage('node')
      ->willReturn($entityStorage->reveal());

    $this->assertEmpty($this->plugin->build());
  }

  /**
   * Tests the getCacheTags method.
   */
  public function testGetCacheTags() {
    // If a gallery node is not set.
    $this->assertEquals([], $this->plugin->getCacheTags());

    // If gallery node is set.
    $this->plugin->setConfiguration(['gallery' => 1]);
    $this->assertEquals(['node:1'], $this->plugin->getCacheTags());
  }

  /**
   * Tests blockForm().
   */
  public function testBlockForm() {
    $form = [];
    $return = $this->plugin->blockForm($form, $this->formState);
    $this->assertEquals('entity_autocomplete', $return['display']['gallery']['#type']);
    $this->assertEmpty($return['display']['gallery']['#default_value']);
    $this->assertTrue($return['display']['show_title']['#default_value']);
    $this->assertFalse($return['display']['slider_nav']['#default_value']);
  }

  /**
   * Tests that the blockForm has a default gallery node selected.
   *
   * @covers ::blockForm()
   */
  public function testBlockFormHasDefaultGallery() {
    $form = [];

    // A default node is set.
    $this->plugin->setConfiguration(['gallery' => 1]);

    $node = $this->prophesize(NodeInterface::CLASS);

    $entityStorage = $this->prophesize(EntityStorageInterface::CLASS);
    $entityStorage->load(1)->willReturn($node->reveal());

    $this->entityTypeManager->getStorage('node')
      ->willReturn($entityStorage->reveal());

    $return = $this->plugin->blockForm($form, $this->formState);
    $this->assertInstanceOf('Drupal\node\NodeInterface', $return['display']['gallery']['#default_value']);
  }

}
