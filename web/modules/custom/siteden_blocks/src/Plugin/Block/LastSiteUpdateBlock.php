<?php

namespace Drupal\siteden_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Site Last Updated' Block.
 */
#[Block(
  id: "last_site_update_block",
  admin_label: new TranslatableMarkup("Website Last Updated"),
  category: new TranslatableMarkup("SiteDen")
)]
class LastSiteUpdateBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {


    $entityTypeManager = \Drupal::service('entity_type.manager');

    $types = [];
    $contentTypes = $entityTypeManager->getStorage('node_type')->loadMultiple();
    foreach ($contentTypes as $contentType) {
      $types[$contentType->id()] = $contentType->label();
    }

    $output = "";
    $node_array = [];
    $most_recent_date = '1655052360';
    foreach($types as $type_id => $type_title){
      $query = \Drupal::entityQuery('node');
      $query->condition('type', $type_id);
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      if(count($nids) > 0){
        foreach($nids as $nid){
          $thisNode = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
          if($thisNode->get('moderation_state')->getString() == "published"){
            $published_date = $thisNode->getCreatedTime();
            $updated_date = $thisNode->getChangedTime();
            if($published_date > $most_recent_date){
              $most_recent_date = $published_date;
            }
            if($updated_date > $most_recent_date){
              $most_recent_date = $updated_date;
            }
          }
        }
      }
    }
    $newest_change_date = $most_recent_date;
    $newest_change_date_string = date('F j\, Y \a\t g:i a', $newest_change_date);
    $meridiem = substr($newest_change_date_string, -2);
    $pre_meridiem_date = substr($newest_change_date_string, 0, -2);
    if($meridiem=="am"){
      $meridiem = "a.m.";
    }
    if($meridiem=="pm"){
      $meridiem = "p.m.";
    }
    $newest_change_date_string = $pre_meridiem_date.$meridiem;
    $newest_change_message = "<i>Last updated: ".$newest_change_date_string."</i>";

    return [
      '#markup' => $newest_change_message,
    ];
  }

}