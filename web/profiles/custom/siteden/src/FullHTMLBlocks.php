<?php

use Drupal\block_content\Entity\BlockContent;
use Drupal\node\Entity\Node;


// Count

$allblocks = \Drupal::database()
  ->select('block_content_field_data', 'bcf')
  ->fields('bcf', ['id'])
  ->condition('reusable', 1)
  ->condition('type', 'sf_basic')
  ->execute();

$rblocks = [];

foreach ($allblocks as $allblock) {
  $rblock_content = BlockContent::load($allblock->id);

  $rbody = $rblock_content->get('body')->value;
  $rbody_format = $rblock_content->get('body')->format;

  if ($rbody_format === 'sf_full_html') {
    $rblocks[] = [
      "id" => $rblock_content->id(),
      "body" => $rbody,
    ];
  }
}


// Get all the entity ids of all the nodes referencing a field called
// layout_builder__layout. If you want the blocks and/or layouts of another
// entity type, the database table and entity loading should be switched out
// with something corresponding.
$rows = \Drupal::database()
  ->select('node__layout_builder__layout', 'ns')
  ->fields('ns', ['entity_id'])
  ->groupBy('ns.entity_id')
  ->execute();
// This will hold all of our block plugin ids.
$block_plugin_ids = [];
// And this will keep track of which layout ids we have.
$layout_ids = [];
$blocks = [];
foreach ($rows as $row) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = Node::load($row->entity_id);
  if (!$node) {
    // If we can not load the node, something must be wrong.
    continue;
  }
  if (!$node->hasField('layout_builder__layout') || $node->get('layout_builder__layout')
      ->isEmpty()) {
    // If the field is empty or does not exist, something must be wrong.
    continue;
  }
  // This will get all of the sections of the node.
  $layout = $node->get('layout_builder__layout')->getValue();
  foreach ($layout as $item) {
    /** @var \Drupal\layout_builder\Section $section */
    $section = $item['section'];
    // This will overwrite the array key of the layout ID if it exists. But that
    // is ok.
    $layout_ids[$section->getLayoutId()] = TRUE;
    // You can also operate directly on the section object, but getting the
    // array structure makes it more convenient for this case.
    $section_array = $section->toArray();
    foreach ($section_array["components"] as $component) {
      // This ID will correspond to the ID we have in the plugin. For example,
      // the page title block has an ID of "page_title_block".

      $id = $component["configuration"]["id"];
      // We only want unique plugin IDs.
      //      if (in_array($id, $block_plugin_ids)) {
      //        continue;
      //      }
      $block_plugin_ids[] = $id;
      if ($id === 'inline_block:sf_basic') {
        $block = \Drupal::entityTypeManager()
          ->getStorage('block_content')
          ->loadRevision($component["configuration"]["block_revision_id"]);
        $body = $block->get('body')->value;
        $body_format = $block->get('body')->format;

        if ($body_format === 'sf_full_html') {
          $blocks[] = [
            "nid" => $node->id(),
            "block_revision_id" => $component["configuration"]["block_revision_id"],
            "body" => $body,
          ];
        }
      }
    }
  }
}
//print_r($nids);
//// This will now be an array of plugin ids like so:
//// ['page_title_block', 'my_other_plugin'].
//print_r($block_plugin_ids);
//// This will now be an array with layout ids as keys. Like so:
//// ['layout_onecol' => TRUE, 'layout_twocol' => TRUE].
//print_r($layout_ids);


if (count($extra) && $extra[0] === 'summary') {
  echo count($blocks) + count($rblocks) . "\n";
}
else {
  if (count($rblocks)) {
    echo "---------------------------------------------------------------------\n";
    print_r($rblocks);
  }
  if (count($blocks)) {
    echo "---------------------------------------------------------------------\n";
    print_r($blocks);
  }

  echo "---------------------------------------------------------------------\n";
  echo "Full HTML in reusable blocks:  " . count($rblocks) . "\n";
  echo "Full HTML in layout blocks:    " . count($blocks) . "\n";
}


