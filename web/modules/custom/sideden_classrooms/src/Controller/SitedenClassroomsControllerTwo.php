<?php

namespace Drupal\siteden_classrooms\Controller;

use \Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\Core\File\FileSystemInterface;

/**
 * An example controller.
 */
class SitedenClassroomsControllerTwo extends ControllerBase {

  /**
   * Returns a renderable array for a test page.
   *
   * return []
   */
  public function import() {


    if (($open = fopen("modules/custom/sideden_classrooms/src/Controller/classrooms_2.csv", "r")) !== false) {
        $count = 0;
        $img_count = 0;
        $file_repository = \Drupal::service('file.repository');
        while (($data = fgetcsv($open, 1000, ",")) !== false) {
          if($count > 0 && $count < 500){
            $array[] = [
              'title' => $data[1]." ".$data[2],
              'building' => $data[1],
              'room' => $data[2],
              'capacity' => (int)$data[3],
              'room_type' => $data[4],
              'moveable_tables' => $data[5],
              'moveable_chairs' => $data[6],
              'moveable_desk' => $data[7],
              'av_display' => $data[8],
              'source_options' => [
                'blueray' => $data[9],
                'dvd' => $data[10],
                'vhs' => $data[11],
                'document_camera' => $data[12],
                'hdmi' => $data[13],
                'vga' => $data[14],
                'wireless_projection' => $data[15],
              ],
              'voice_amp' => $data[16],
              'class_computer' => $data[17],
              'video_playback' => $data[18],
              'sound_system' => $data[19],
              'interactive' => $data[20],
              'bruincast_video' => $data[21],
              'bruincast_audio' => $data[22],
              'wireless_network' => $data[23],
              'zoom' => $data[24],
              'box_link' => $data[25]
            ];
            $s = [];
            if($data[9] == 1){
              $s[] = 'blu_ray';
            }
            if($data[10] == 1){
              $s[] = 'dvd';
            }
            if($data[11] == 1){
              $s[] = 'vhs';
            }
            if($data[12] == 1){
              $s[] = 'document_camera';
            }
            if($data[13] == 1){
              $s[] = 'hdmi_input';
            }
            if($data[14] == 1){
              $s[] = 'vga_input';
            }
            if($data[15] == 1){
              $s[] = 'wireless_projection';
            }
            if($data[12] == "Transparency Projector"){
              $s[] = 'transparency_projector';
            }
            $capacity = (int)$data[3];
            if($capacity < 20){
              $capacity_range = "10_19";
            } 
            if($capacity > 19 && $capacity < 40){
              $capacity_range = "20_39";
            } 
            if($capacity > 39 && $capacity < 60){
              $capacity_range = "40_59";
            }
            if($capacity > 59 && $capacity < 100){
              $capacity_range = "60_99";
            }
            if($capacity > 99 && $capacity < 150){
              $capacity_range = "100_149";
            }
            if($capacity > 149 && $capacity < 200){
              $capacity_range = "150_199";
            }
            if($capacity > 199 && $capacity < 300){
              $capacity_range = "200_299";
            }
            if($capacity > 299){
              $capacity_range = "300";
            }

            $filename = $data[1]."-".$data[2];
            $filename = str_replace('Kaplan', 'Humanities', $filename);
            $img_src = "modules/custom/sideden_classrooms/src/Controller/classroom-search-images/".str_replace(' ', '-', $filename).".jpeg";
            $image_data = file_get_contents($img_src);
            if($image_data == FALSE){
              $img_count++;
              ksm($img_src);
            } else {
              $image = $file_repository->writeData($image_data, "public://".$filename.".jpeg", FileSystemInterface::EXISTS_REPLACE);
              $image_media = Media::create([
                'name' => $filename,
                'bundle' => 'sf_image_media_type',
                'uid' => 1,
                'langcode' => 'en',
                'status' => 0,
                'field_media_image' => [
                  'target_id' => $image->id(),
                  'alt' => t($filename),
                  'title' => t($filename),
                ]
               ]);
              $image_media->setPublished();
              $image_media->save();         
            }
            $livestream_text = NULL;
            if($data[24]=="Camera Connection Only"){
              $livestream_text = $data[24];
              $data[24] = "1";
            }
            // Create a new node object.
            $node = Node::create([
              'type' => 'classroom',
              'title' => $data[1]." ".$data[2],
              'field_building' => strtolower($data[1]),
              'field_room' => $data[2],
              'field_capacity' => $capacity,
              'field_capacity_range' => $capacity_range,
              'field_av_display' => strtolower(str_replace(' ', '_', $data[8])),
              'field_av_source_options' => $s,
              'field_bruincast_audio' => (int)$data[22],
              'field_bruincast_video' => (int)$data[21],
              'field_classroom_computer' => (int)$data[17],
              'field_interactive_display' => (int)$data[20],
              'field_moveable_chairs' => (int)$data[6],
              'field_moveable_tables' => (int)$data[5],
              'field_tablet_desk' => (int)$data[7],
              'field_room_type' => strtolower(str_replace(' ', '_', $data[4])),
              'field_voice_amplification' => (int)$data[16],
              'field_zoom_streaming' => (int)$data[24],
              'field_video_playback' => $data[18],
              'field_sound_system' => $data[19],
              'field_wireless_network' => (int)$data[23],
              'field_live_stream_text' => $livestream_text,
              'field_classroom_photo' => $image_media->id(),
              'field_classroom_layout' => [
                'uri' => $data[25],
                'title' => 'Room Seating Layout'
              ]
            ]);

            // Save the node.
            $node->save();
          }

          $count++;
        }
     
        fclose($open);
    }
    $output = $count." - ".$img_count."<br/><br/><pre>";
    // To display array data
    $output .= var_export($array,true);
    $output .= "</pre>";

    $build = [
      '#markup' => $output,
    ];
    return $build;

  }

}