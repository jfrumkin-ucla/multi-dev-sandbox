<?php

namespace Drupal\Tests\ck_columns\Unit\Plugin\Filter;

use Drupal\Tests\UnitTestCase;
use Drupal\ck_columns\Plugin\Filter\FilterColumns;
use Drupal\filter\FilterProcessResult;

/**
 * @coversDefaultClass \Drupal\ck_columns\Plugin\Filter\FilterColumns
 * @group ck_columns
 */
class FilterColumnsTest extends UnitTestCase {

  /**
   * @var \Drupal\ck_columns\Plugin\Filter\FilterColumns
   */
  protected $plugin;

  /**
   * Create the setup for plugin and __construct.
   */
  protected function setUp(): void {
    parent::setUp();

    $configuration = [];
    $plugin_id = 'ck_columns';
    $plugin_definition['provider'] = 'ck_columns';

    $this->plugin = new FilterColumns(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Tests the create method.
   *
   * @see ::create()
   */
  public function testCreate() {
    $configuration = [];
    $plugin_id = 'ck_columns';
    $plugin_definition['provider'] = 'ck_columns';

    $instance = new FilterColumns($configuration, $plugin_id, $plugin_definition);
    $this->assertInstanceOf(FilterColumns::CLASS, $instance);
  }

  /**
   * Tests the process method.
   *
   * @see ::process()
   */
  public function testProcess() {
    $text = <<<EOL
<layout-columns custom-class="test-class" columns="3" layout="25-75" gap="1" cwidth="full" vmargin="3" force="true">
  <div slot="column1"><p>First</p></div>
  <div slot="column2"><p>Second</p></div>
  <div slot="column3"><p>Third</p></div>
  <div slot="column4"><p>Fourth</p></div>
</layout-columns>
EOL;

    $expected = <<<EOL
<layout-columns class="test-class" columns="3" layout="25-75" gap="1" cwidth="full" vmargin="3" force="true">
  <div slot="column1"><p>First</p></div>
  <div slot="column2"><p>Second</p></div>
  <div slot="column3"><p>Third</p></div>
</layout-columns>
EOL;

    $return = $this->plugin->process($text, 'en');

    $this->assertInstanceOf(FilterProcessResult::CLASS, $return);
    $this->assertXmlStringEqualsXmlString($expected, $return->getProcessedText());
  }

  /**
   * Tests the nested layouts.
   *
   * @see ::process()
   */
  public function testNestedLayouts() {
    $text = <<<EOL
<layout-columns class="existing" custom-class="test-class" columns="4" layout="25-75" gap="1" cwidth="full" vmargin="3" force="true">
  <div slot="column1"><p>First</p></div>
  <div slot="column2"><p>Second</p></div>
  <div slot="column3"><p>Third</p>
    <layout-columns custom-class="nested" columns="2" layout="67-33" gap="2" cwidth="shrink" vmargin="4" force="false">
      <div slot="column1">Nested 1</div>
      <div slot="column2">Nested 2</div>
      <div slot="column3">Nested 3</div>
      <div slot="column4">Nested 4</div>
    </layout-columns>
  </div>
  <div slot="column4"><p>Fourth</p></div>
</layout-columns>
EOL;

    $expected = <<<EOL
<layout-columns class="existing test-class" columns="4" layout="25-75" gap="1" cwidth="full" vmargin="3" force="true">
  <div slot="column1"><p>First</p></div>
  <div slot="column2"><p>Second</p></div>
  <div slot="column3"><p>Third</p>
    <layout-columns class="nested" columns="2" layout="67-33" gap="2" cwidth="shrink" vmargin="4" force="false">
      <div slot="column1">Nested 1</div>
      <div slot="column2">Nested 2</div>
    </layout-columns>
  </div>
  <div slot="column4"><p>Fourth</p></div>
</layout-columns>
EOL;

    $return = $this->plugin->process($text, 'en');

    $this->assertInstanceOf(FilterProcessResult::CLASS, $return);
    $this->assertXmlStringEqualsXmlString($expected, $return->getProcessedText());
  }

}
