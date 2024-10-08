<?php

namespace Drupal\siteden_update;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\features\FeaturesManagerInterface;
use Drupal\Component\Serialization\Yaml;

/**
 * Class UpdateHelper.
 */
class UpdateHelper implements UpdateHelperInterface {

  /**
   * The Features manager service.
   *
   * @var \Drupal\features\FeaturesManagerInterface
   */
  protected $manager;

  /**
   * The module installer.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * Constructs the service.
   *
   * @param \Drupal\features\FeaturesManagerInterface $manager
   *   The Features Manager.
   * @param \Drupal\Core\Extension\ModuleInstallerInterface $moduleInstaller
   *   The module installer.
   */
  public function __construct(FeaturesManagerInterface $manager, ModuleInstallerInterface $moduleInstaller) {
    $this->manager = $manager;
    $this->moduleInstaller = $moduleInstaller;
  }

  /**
   * {@inheritDoc}
   */
  public function installModules(array $modules) {
    $this->moduleInstaller->install($modules);
  }

  /**
   * {@inheritDoc}
   */
  public function uninstallModules(array $modules) {
    $this->moduleInstaller->uninstall($modules);
  }

  /**
   * {@inheritDoc}
   */
  public function revertFeatures(array $modules) {
    $this->manager->import($modules);
  }

  /**
   * {@inheritDoc}
   */
  public function revertAllFeatures() {
    $packages = $this->manager->getFeaturesModules(NULL, TRUE);
    $modules = [];
    foreach ($packages as $package) {
      /** @var \Drupal\Core\Extension\Extension $package */
      $modules[$package->getName()];
    }
    $this->revertFeatures($modules);
  }

  /**
   * Helper function to update configurations that may already exist.
   *
   * Should be used by all modules which update configurations that
   * may already exist, for example views.view.search.yml.
   *
   * @param string $config_name
   *   Name of configuration, which should be in module_path/config/udpate.
   * @param string $module
   *   Module name that contains configuration.
   */
  public function siteden_update_update_or_install_config(String $config_name, String $module, String $type = 'module', String $opt = 'install') {
    $updated = [];
    $created = [];
    /** @var \Drupal\Core\Config\ConfigManagerInterface $config_manager */
    $config_manager = \Drupal::service('config.manager');
    // This line can be updated to reflect the correct directory.
    $files = glob(\Drupal::service('extension.path.resolver')->getPath($type, $module) . '/config/' . $opt . '/' . $config_name . '.yml');
    foreach ($files as $file) {
      $raw = file_get_contents($file);
      $value = Yaml::decode($raw);
      if (!is_array($value)) {
        throw new RuntimeException(sprintf('Invalid YAML file %s'), $file);
      }
      $type = $config_manager->getEntityTypeIdByName(basename($file));
      $entity_manager = $config_manager->getEntityTypeManager();
      $definition = $entity_manager->getDefinition($type);
      $id_key = $definition->getKey('id');
      $id = $value[$id_key];
      /** @var \Drupal\Core\Config\Entity\ConfigEntityStorage $entity_storage */
      $entity_storage = $entity_manager->getStorage($type);
      $entity = $entity_storage->load($id);
      if ($entity) {
        $entity = $entity_storage->updateFromStorageRecord($entity, $value);
        $entity->save();
        $updated[] = $id;
      }
      else {
        $entity = $entity_storage->createFromStorageRecord($value);
        $entity->save();
        $created[] = $id;
      }
    }
    return [
      'updated' => $updated,
      'created' => $created,
    ];
  }

}
