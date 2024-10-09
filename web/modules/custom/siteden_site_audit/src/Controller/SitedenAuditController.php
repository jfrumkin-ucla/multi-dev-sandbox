<?php

namespace Drupal\siteden_site_audit\Controller;

use \Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class SitedenAuditController extends ControllerBase {

  /**
   * Returns a renderable array for a test page.
   *
   * return []
   */
  public function audit() {

    $entityTypeManager = \Drupal::service('entity_type.manager');

    $types = [];
    $contentTypes = $entityTypeManager->getStorage('node_type')->loadMultiple();
    foreach ($contentTypes as $contentType) {
      $types[$contentType->id()] = $contentType->label();
    }

    $paragraph_types = [];
    $paragraphTypes = $entityTypeManager->getStorage('paragraphs_type')->loadMultiple();
    foreach ($paragraphTypes as $paragraphType) {
      $paragraph_types[$paragraphType->id()] = $paragraphType->label();
    }

    $output = "";
    $node_array = [];
    foreach($types as $type_id => $type_title){
      $query = \Drupal::entityQuery('node');
      $query->condition('type', $type_id);
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      if(count($nids) > 0){
        $node_array[$type_title] = count($nids);
      }
    }
    asort($node_array);

    $paragraph_array = [];
    foreach($paragraph_types as $ptype_id => $ptype_title){
      $query = \Drupal::entityQuery('paragraph');
      $query->condition('type', $ptype_id);
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      if(count($nids) > 0){
        $paragraph_array[$ptype_title] = count($nids);
      }
    }
    asort($paragraph_array);

    $output .= "<h2>Nodes</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($node_array as $bundle_title => $bundle_count){
      $output .= "<tr><td>".$bundle_title."</td><td>".$bundle_count."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Paragraphs</h2>";
    $output .= "<table class='audit-table'>";
    foreach($paragraph_array as $bundle_title => $bundle_count){
      $output .= "<tr><td>".$bundle_title."</td><td>".$bundle_count."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h1>Unused</h1>";


    $node_array = [];
    foreach($types as $type_id => $type_title){
      $query = \Drupal::entityQuery('node');
      $query->condition('type', $type_id);
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      if(count($nids) == 0){
        $node_array[$type_title] = count($nids);
      }
    }
    ksort($node_array);

    $paragraph_array = [];
    foreach($paragraph_types as $ptype_id => $ptype_title){
      $query = \Drupal::entityQuery('paragraph');
      $query->condition('type', $ptype_id);
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      if(count($nids) == 0){
        $paragraph_array[$ptype_title] = count($nids);
      }
    }
    ksort($paragraph_array);

    $output .= "<h2>Nodes</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($node_array as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".$bundle_title."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Paragraphs</h2>";
    $output .= "<table class='audit-table'>";
    foreach($paragraph_array as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".$bundle_title."</td></tr>";
    }
    $output .= "</table>";



    $build = [
      '#markup' => $output,
    ];
    return $build;

  }

}