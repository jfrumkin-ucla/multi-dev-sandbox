<?php

namespace Drupal\Tests\ck_media_link\Unit\Plugin\Filter;

use Drupal\Core\Render\Renderer;
use Drupal\redirect\Entity\Redirect;
use Drupal\redirect\RedirectRepository;
use Drupal\Tests\ck_media_link\Unit\Plugin\Filter\Mocks\MockFilterMediaLink;
use Drupal\Tests\UnitTestCase;
use Drupal\ck_media_link\Plugin\Filter\FilterMediaLink;
use Drupal\Core\Path\PathValidator;
use Drupal\path_alias\AliasManager;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\filter\FilterProcessResult;

/**
 * @coversDefaultClass \Drupal\ck_media_link\Plugin\Filter\FilterMediaLink
 * @group ck_media_link
 */
class FilterMediaLinkTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\Path\PathValidator
   */
  protected $pathValidator;

  /**
   * @var \Drupal\Core\Path\AliasManager
   */
  protected $aliasManager;

  /**
   * @var \Drupal\redirect\RedirectRepository
   */
  protected $redirectRepository;

  /**
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * @var \Drupal\ck_media_link\Plugin\Filter\FilterMediaLink
   */
  protected $plugin;

  /**
   * Create the setup for plugin and __construct.
   */
  protected function setUp(): void {
    parent::setUp();

    // Stub path validator.
    $this->pathValidator = $this->prophesize(PathValidator::CLASS);
    $this->pathValidator->isValid('http://link.test')->willReturn(TRUE);

    // Stub alias manager.
    $this->aliasManager = $this->prophesize(AliasManager::CLASS);
    $this->aliasManager->getAliasByPath('http://link.test')
      ->willReturn('http://link.test');

    // Stub redirect repository.
    $this->redirectRepository = $this->prophesize(RedirectRepository::CLASS);
    $this->redirectRepository->findBySourcePath(Argument::any())
      ->willReturn([]);

    // Stub renderer.
    $this->renderer = $this->prophesize(Renderer::CLASS);
    $this->renderer->render(Argument::type('array'))->will(function ($args) {
      $url = $args[0]['#url'];
      $image = $args[0]['#image'];
      $title = $args[0]['#title'];
      $content = $args[0]['#content'];

      $output = <<<EOL
<a href="$url">
  <div>$image</div>
  <h1>$title</h1>
  <div>$content</div>
</a>
EOL;

      return $output;
    });

    $configuration = [];
    $plugin_id = 'ck_media_link';
    $plugin_definition['provider'] = 'ck_media_link';

    $this->plugin = new FilterMediaLink(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $this->pathValidator->reveal(),
      $this->aliasManager->reveal(),
      $this->redirectRepository->reveal(),
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
    $plugin_id = 'ck_media_link';
    $plugin_definition['provider'] = 'ck_media_link';

    $container = $this->prophesize(ContainerInterface::CLASS);
    $container->get('path.validator')->willReturn($this->pathValidator);
    $container->get('path_alias.manager')->willReturn($this->aliasManager);
    $container->get('redirect.repository')
      ->willReturn($this->redirectRepository);
    $container->get('renderer')->willReturn($this->renderer);

    $instance = FilterMediaLink::create($container->reveal(), $configuration, $plugin_id, $plugin_definition);
    $this->assertInstanceOf(FilterMediaLink::CLASS, $instance);
  }

  /**
   * Tests the process method.
   *
   * @see ::process()
   */
  public function testProcess() {
    $text = <<<EOL
<media-link url="http://link.test">
  <div slot="image"><drupal-media data-entity-type="media" data-entity-uuid="placeholder"></drupal-media></div>
  <div slot="title">Title</div>
  <div slot="content"><p>Content</p></div>
</media-link>
EOL;

    $expected = <<<EOL
<a href="http://link.test">
  <div><drupal-media data-entity-type="media" data-entity-uuid="placeholder"></drupal-media></div>
  <h1>Title</h1>
  <div><p>Content</p></div>
</a>
EOL;

    $return = $this->plugin->process($text, 'en');

    $this->assertInstanceOf(FilterProcessResult::CLASS, $return);
    $this->assertEquals($expected, $return->getProcessedText());
  }

  /**
   * Tests the formatPath method.
   *
   * @see ::formatPath()
   */
  public function testFormatPath() {
    // Path is valid.
    $expected = 'http://link.test';
    $path = 'http://link.test';

    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);

    // Path is HTTPS.
    $expected = 'https://link.test';
    $path = 'https://link.test';

    $this->pathValidator->isValid($path)->willReturn(TRUE);
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);

    // Path is internal.
    $expected = '/link-test';
    $path = '/link-test';

    $this->pathValidator->isValid($path)->willReturn(TRUE);
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);

    // Path is relative and valid.
    $expected = '/link-test';
    $path = 'link-test';

    $this->pathValidator->isValid($path)->willReturn(TRUE);
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);

    // Path is relative and invalid internal link (ex www.google.com)
    $expected = 'http://www.invalid-internal';
    $path = 'www.invalid-internal';

    $this->pathValidator->isValid('/' . $path)->willReturn(FALSE);
    $this->aliasManager->getAliasByPath('/' . $path)->willReturn('/' . $path);
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);

    // Path is relative invalid but is valid internal alias.
    $expected = '/valid-alias';
    $path = '/valid-alias';

    $this->pathValidator->isValid($path)->willReturn(FALSE);
    $this->aliasManager->getAliasByPath($path)->willReturn('node/1');
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);
  }

  /**
   * Tests the formatPath method when containing a redirect.
   *
   * @see ::formatPath()
   */
  public function testFormatPathHasRedirect() {
    // Path is valid.
    $expected = '/new';
    $internal_uri = 'internal:/node/1';
    $path = '/original';
    $path_trimmed = 'original';
    $path_redirected = $expected;

    $configuration = [];
    $plugin_id = 'ck_media_link';
    $plugin_definition['provider'] = 'ck_media_link';

    $this->plugin = new MockFilterMediaLink(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $this->pathValidator->reveal(),
      $this->aliasManager->reveal(),
      $this->redirectRepository->reveal(),
      $this->renderer->reveal()
    );

    // Stub redirect.
   $redirect = $this->prophesize(Redirect::CLASS);
    $redirect->getRedirect(Argument::any())
      ->willReturn(['uri' => $internal_uri]);

    $this->redirectRepository->findBySourcePath($path_trimmed)
      ->willReturn([$redirect->reveal()]);

    $this->pathValidator->isValid($path_redirected)->willReturn(TRUE);
    $return = $this->plugin->formatPath($path);

    $this->assertEquals($expected, $return);
  }

}
