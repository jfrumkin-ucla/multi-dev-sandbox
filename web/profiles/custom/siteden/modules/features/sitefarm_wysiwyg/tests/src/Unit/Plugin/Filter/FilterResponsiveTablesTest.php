<?php

namespace Drupal\Tests\sitefarm_wysiwyg\Unit\Plugin\Filter;

use Drupal\Tests\UnitTestCase;
use Drupal\sitefarm_wysiwyg\Plugin\Filter\FilterResponsiveTables;
use Drupal\filter\FilterProcessResult;

/**
 * @coversDefaultClass \Drupal\sitefarm_wysiwyg\Plugin\Filter\FilterResponsiveTables
 * @group sitefarm_wysiwyg
 */
class FilterResponsiveTablesTest extends UnitTestCase {

  /**
   * @var \Drupal\sitefarm_wysiwyg\Plugin\Filter\FilterResponsiveTables
   */
  protected $plugin;

  /**
   * Create the setup for plugin and __construct.
   */
  protected function setUp(): void {
    parent::setUp();

    $configuration = [];
    $plugin_id = 'filter_responsive_tables';
    $plugin_definition['provider'] = 'filter_responsive_tables';

    $this->plugin = new FilterResponsiveTables(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Tests the process method.
   *
   * @dataProvider processProvider
   *
   * @see ::process()
   */
  public function testProcess($text, $expected) {
    $return = $this->plugin->process($text, 'en');

    $this->assertInstanceOf(FilterProcessResult::CLASS, $return);
    $this->assertXmlStringEqualsXmlString($expected, $return->getProcessedText());
  }

  /**
   * Provider for testProcess()
   */
  public function processProvider() {
    return [
      [
        // Provided.
        <<<EOL
<table class="test">
  <tr>
    <td>Test</td>
  </tr>
</table>
EOL,
        // Expected.
        <<<EOL
<div role="region" aria-label="Scrollable Table" tabindex="0" class="responsive-table">
  <table class="test">
    <tr>
      <td>Test</td>
    </tr>
  </table>
</div>
EOL,
      ],
      // Nested table.
      [
        <<<EOL
<table>
  <tr>
    <td>
      <table>
        <tr>
          <td>Test</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
EOL,
        <<<EOL
<div role="region" aria-label="Scrollable Table" tabindex="0" class="responsive-table">
  <table>
    <tr>
      <td>
        <table>
          <tr>
            <td>Test</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
EOL,
      ],
      // Multiple tables.
      [
        <<<EOL
<section>
  <table>
    <tr>
      <td>Table 1</td>
    </tr>
  </table>
  <p>text</p>
  <table>
    <tr>
      <td>Table 2</td>
    </tr>
  </table>
</section>
EOL,
        <<<EOL
<section>
  <div role="region" aria-label="Scrollable Table" tabindex="0" class="responsive-table">
    <table>
      <tr>
        <td>Table 1</td>
      </tr>
    </table>
  </div>
  <p>text</p>
  <div role="region" aria-label="Scrollable Table" tabindex="0" class="responsive-table">
    <table>
      <tr>
        <td>Table 2</td>
      </tr>
    </table>
  </div>
</section>
EOL,
      ],
      // Use table caption for aria-label.
      [
        <<<EOL
<table>
  <caption>"I'm a <a href="#">Caption</a>"</caption>
  <tr>
    <td>
      <table>
        <caption>Nested Caption</caption>
        <tr>
          <td>Test</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
EOL,
        <<<EOL
<div role="region" aria-label="&quot;I'm a Caption&quot;" tabindex="0" class="responsive-table">
  <table>
    <caption>"I'm a <a href="#">Caption</a>"</caption>
    <tr>
      <td>
        <table>
          <caption>Nested Caption</caption>
          <tr>
            <td>Test</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
EOL,
      ],

    ];
  }

}
