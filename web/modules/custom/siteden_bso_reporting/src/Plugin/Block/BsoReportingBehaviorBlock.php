<?php

namespace Drupal\siteden_bso_reporting\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;


/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "bso_reporting_behavior_block",
 *   admin_label = @Translation("BSO Reporting Behavior block"),
 *   category = @Translation("Custom Form Block")
 * )
 */
class BsoReportingBehaviorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\siteden_bso_reporting\Form\SitedenBsoReportingBehaviorForm');

    return $form;
  }

}