<?php

function er_pilot_verification () {
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $event_id = isset($_POST['event']) ? $_POST['event'] : null;

  $response = array(
    'data' => array(
      'isActive' => false,
      'isRegistered' => false
    ),
    'ok' => false
  );

  if (isset($id) && isset($event_id)) {
    $pilot = get_post($id);
    $event = get_post($event_id);
    $event_date = date_create_from_format('d/m/Y', get_field('er_event_date', $event_id));
    $current_date = time();
    $isEventActive = $event_date !== false ? $event_date->getTimestamp() >= $current_date : false;

    if (
      isset($pilot) &&
      strcmp($pilot->post_type, "er_racer") === 0 &&
      isset($event) &&
      strcmp($event->post_type, "er_evento") === 0 &&
      $isEventActive
    ) {
      $pilot_email = get_field('er_racer_email', $id);
      $categories = get_the_terms($pilot, 'er_racer_category');
      $pilot_category = array();
      $is_registered = false;
  
      if (count($categories) > 0) {
        $pilot_category = array(
          "term_id" => $categories[0]->term_id,
          "name" => $categories[0]->name
        );
      }
  
      $pilot_data = array(
        "ID" => $id,
        "post_title" => $pilot->post_title,
        "email" => $pilot_email,
        "category" => $pilot_category,
        "post_status" => $pilot->post_status
      );
  
      $args = array(
        'post_type' => 'inscripcion',
        'posts_per_page' => -1, // Obtener todos los posts del custom post type
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => array('publish', 'pending'),
      );
    
      $registrations = get_posts($args);
  
      foreach ($registrations as $registration) {
        $old_pilot_id = get_field('er_registration_pilot', $registration->ID);
        $old_event_id = get_field('er_registration_event', $registration->ID);
        
        if (strcmp($id, $old_pilot_id) === 0 && strcmp($event_id, $old_event_id) === 0) {
          $is_registered = true;
          break;
        }
      }
  
      wp_reset_postdata(); // Restore post global object
  
      $response = array(
        'data' => array(
          'pilot' => $pilot_data,
          'event' => $event,
          'isActive' => strcmp($pilot->post_status, "publish") === 0,
          'isRegistered' => $is_registered
        ),
        'ok' => true
      );
    }
  }

  print_r(json_encode($response));

  die();
}

add_action('wp_ajax_er_pilot_verification', 'er_pilot_verification');
add_action('wp_ajax_nopriv_er_pilot_verification', 'er_pilot_verification');