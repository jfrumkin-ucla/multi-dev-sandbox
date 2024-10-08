<?php

namespace Drupal\Tests\siteden\Unit;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\siteden\ProfileInstall;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Prophecy\Argument;

/**
 * @coversDefaultClass \Drupal\siteden\ProfileInstall
 * @group siteden
 */
class ProfileInstallTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Instance of the Module Installer service.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * @var \Drupal\siteden\ProfileInstall
   */
  protected $helper;

  /**
   * Create the setup for constants.
   */
  protected function setUp(): void {
    parent::setUp();

    $this->entityTypeManager = $this->prophesize(EntityTypeManagerInterface::CLASS);

    $this->moduleInstaller = $this->prophesize(ModuleInstallerInterface::CLASS);

    $this->helper = new ProfileInstall(
      $this->entityTypeManager->reveal(),
      $this->moduleInstaller->reveal()
    );
  }

  /**
   * Tests the hideAndSetDefaultRegion() method.
   */
  public function testHideAndSetDefaultRegion() {
    $form = [
      'regional_settings' => [
        '#type' => 'visible',
        'site_default_country' => ['#default_value' => 'country'],
        'date_default_timezone' => ['#default_value' => 'timezone'],
      ],
    ];

    $expected = [
      'regional_settings' => [
        '#type' => 'hidden',
        'site_default_country' => ['#default_value' => 'US'],
        'date_default_timezone' => ['#default_value' => 'America/Los_Angeles'],
      ],
    ];

    $this->helper->hideAndSetDefaultRegion($form);
    $this->assertEquals($expected, $form);
  }

  /**
   * Tests the removeUpdateNotificationOptions() method.
   */
  public function testRemoveUpdateNotificationOptions() {
    $form = [
      'update_notifications' => '',
    ];

    $this->helper->removeUpdateNotificationOptions($form);
    $this->assertEmpty($form);
  }

  /**
   * Tests the useMailInContactForm() method.
   */
  // Public function testUseMailInContactForm() {
  //    $form = [];
  //    $this->helper->useMailInContactForm($form);
  //    $this->assertInstanceOf('Drupal\siteden\ProfileInstall', $form['#submit'][0][0]);
  //    $this->assertEquals('useMailInContactFormSubmit', $form['#submit'][0][1]);
  //  }.

  /**
   * Test the defaultContentModuleCleanup() method.
   */
  public function testDefaultContentModuleCleanup() {
    $this->moduleInstaller->uninstall(Argument::any())->shouldBeCalled();
    $this->helper->defaultContentModuleCleanup();
  }

}
