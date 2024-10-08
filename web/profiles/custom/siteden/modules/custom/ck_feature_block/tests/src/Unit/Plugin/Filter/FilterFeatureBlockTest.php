<?php

namespace Drupal\Tests\ck_feature_block\Unit\Plugin\Filter;

use Drupal\Core\Render\Renderer;
use Drupal\Tests\UnitTestCase;
use Drupal\ck_feature_block\Plugin\Filter\FilterFeatureBlock;
use Drupal\filter\FilterProcessResult;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\ck_feature_block\Plugin\Filter\FilterFeatureBlock
 * @group ck_feature_block
 */
class FilterFeatureBlockTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * @var \Drupal\ck_feature_block\Plugin\Filter\FilterFeatureBlock
   */
  protected $plugin;

  /**
   * Create the setup for plugin and __construct.
   */
  protected function setUp(): void {
    parent::setUp();

    $configuration = [];
    $plugin_id = 'ck_feature_block';
    $plugin_definition['provider'] = 'ck_feature_block';

    // Stub renderer.
    $this->renderer = $this->prophesize(Renderer::CLASS);
    $this->renderer->render(Argument::type('array'))->will(function ($args) {
      $class = $args[0]['#class'];
      $title = $args[0]['#title'];
      $body = $args[0]['#body'];

      $output = <<<EOL
<div class="$class">
  <h1>$title</h1>
  <div>$body</div>
</div>
EOL;

      return $output;
    });

    $this->plugin = new FilterFeatureBlock(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $this->renderer->reveal()
    );
  }

  /**
   * Tests the create method.
   *
   * @see ::create()
   */
  public function testCreate() {
    $configuration = [];
    $plugin_id = 'ck_feature_block';
    $plugin_definition['provider'] = 'ck_feature_block';

    $container = $this->prophesize(ContainerInterface::CLASS);
    $container->get('renderer')->willReturn($this->renderer);

    $instance = FilterFeatureBlock::create($container->reveal(), $configuration, $plugin_id, $plugin_definition);
    $this->assertInstanceOf(FilterFeatureBlock::CLASS, $instance);
  }

  /**
   * Tests the process method.
   *
   * @see ::process()
   */
  public function testProcess() {
    $text = <<<EOL
<feature-block class="test-class">
  <div slot="title">Title</div>
  <div slot="body"><p>Content</p></div>
</feature-block>
EOL;

    $expected = <<<EOL
<div class="test-class">
  <h1>Title</h1>
  <div><p>Content</p></div>
</div>
EOL;

    $return = $this->plugin->process($text, 'en');

    $this->assertInstanceOf(FilterProcessResult::CLASS, $return);
    $this->assertEquals($expected, $return->getProcessedText());
  }

}
