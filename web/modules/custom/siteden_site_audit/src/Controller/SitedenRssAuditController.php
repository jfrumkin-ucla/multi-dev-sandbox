<?php

namespace Drupal\siteden_site_audit\Controller;

use \Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class SitedenRssAuditController extends ControllerBase {

  /**
   * Returns a renderable array for a test page.
   *
   * return []
   */
  public function audit() {
$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-chr.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-chr.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-chr.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}

    $output = "<h1>Campus Human Resources</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-transportation.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-transportation.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-transportation.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}

    $output .= "<br/><hr class='darkhr'><br/><h1>Transportation</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruinssafeonline.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruinssafeonline.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruinssafeonline.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Bruins Safe Online</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cru.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cru.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cru.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Central Resource Unit</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-senate.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-senate.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-senate.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Academic Senate</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-apo.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-apo.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-apo.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Academic Affairs and Personnel</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruintech.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruintech.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-bruintech.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>BruinTech</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";



$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-facilities.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-facilities.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-facilities.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Facilities</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";




$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cio.pantheonsite.io/paragraph-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $types[$term->type][] = $term->type;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cio.pantheonsite.io/taxonomy-term-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$vocab_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $vocab_types[$term->vid[0]->target_id][] = $term->vid[0]->target_id;
}

$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://test-ucla-siteden-cio.pantheonsite.io/content-feed');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
$node_types = [];
foreach ($obj as $key => $term) {
  //ksm($term->vid[0]->target_id);
  $node_types[$term->type][] = $term->type;
}


    $output .= "<br/><hr class='darkhr'><br/><h1>Office of the CIO</h1>";


    $output .= "<h2>Node Types</h2>";
    $output .= "<table class='audit-table'>";
    foreach($node_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($node_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";


    $output .= "<h2>Paragraph Types</h2>";
    $output .= "<table class='audit-table' style='width:300px'>";
    foreach($types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $output .= "<h2>Taxonomy Vocabulary</h2>";
    $output .= "<table class='audit-table'>";
    foreach($vocab_types as $bundle_count => $bundle_title){
      $output .= "<tr><td>".$bundle_count."</td><td>".count($vocab_types[$bundle_count])."</td></tr>";
    }
    $output .= "</table>";

    $build = [
      '#markup' => $output,
    ];
    return $build;

  }

}