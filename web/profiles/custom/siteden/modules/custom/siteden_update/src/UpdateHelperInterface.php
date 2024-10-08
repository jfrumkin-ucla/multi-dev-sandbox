<?php

namespace Drupal\siteden_update;

/**
 *
 */
interface UpdateHelperInterface {

  /**
   * Install a module(s).
   *
   * @param array $modules
   *   Module(s) to be reverted.
   *
   * @throws \Drupal\Core\Extension\MissingDependencyException
   */
  public function installModules(array $modules);

  /**
   * Uninstall a module(s).
   *
   * @param array $modules
   *   Module(s) to be reverted.
   */
  public function uninstallModules(array $modules);

  /**
   * Revert a features module(s).
   *
   * @param array $modules
   *   Module(s) to be reverted.
   */
  public function revertFeatures(array $modules);

  /**
   * Import module config from all installed features.
   */
  public function revertAllFeatures();

}
