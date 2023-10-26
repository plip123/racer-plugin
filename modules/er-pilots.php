<?php

function er_pilot_verification () {
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $event_id = isset($_POST['event']) ? $_POST['event'] : null;

  $response = array(
    'data' => array(
      'isActive' => false,
    ),
    'ok' => false
  );

  if (isset($id) && isset($event_id)) {
    $pilot = get_post($id);
    $pilot_email = get_field('er_racer_email', $id);
    $categories = get_the_terms($pilot, 'er_racer_category');
    $pilot_category = array();

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
    $event = get_post($event_id);

    wp_reset_postdata(); // Restore post global object

    $response = array(
      'data' => array(
        'pilot' => $pilot_data,
        'event' => $event,
        'isActive' => strcmp($pilot->post_status, "publish") === 0
      ),
      'ok' => true
    );
  }

  print_r(json_encode($response));

  die();
}

add_action('wp_ajax_er_pilot_verification', 'er_pilot_verification');
add_action('wp_ajax_nopriv_er_pilot_verification', 'er_pilot_verification');