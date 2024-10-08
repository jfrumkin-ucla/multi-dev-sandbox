<?php

/**
 * @file
 * Contains \FeatureContext.
 */

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext {
  // SiteFarmSubContext is automatically loaded by the Drupal Behat Extension.
  // @see sitefarm.behat.inc.
  // @see http://behat-drupal-extension.readthedocs.org/en/3.1/subcontexts.html#for-contributors
}
