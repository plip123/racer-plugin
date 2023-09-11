<?php

function er_log ($data, $message="") {
  print_r(json_encode(array(
    "data" => $data,
    "message" => $message,
  )));
  die();
};

function er_get_calendar () {
  $post_type = 'er_evento';

  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Obtener todos los posts del custom post type
    'orderby' => 'title',
    'order' => 'ASC'
  );

  $events = get_posts($args);
  $calendar = array();

  foreach($events as $event) {
    setup_postdata($event);
    $event_id = $event->ID;
    $data = array(
      "title" => $event->post_title,
      "date" => get_field('er_event_date', $event_id),
      "link" => get_permalink($event_id),
    );
    array_push($calendar, $data);
  }

  wp_reset_postdata(); // Restore post global object

  print_r(json_encode($calendar));
  die();
}
add_action('wp_ajax_er_get_calendar', 'er_get_calendar');
add_action('wp_ajax_nopriv_er_get_calendar', 'er_get_calendar');


function sortByDate ($events) {
  if (!$length = count($events)) {
    return $events;
  }
   
  $base_event = $events[0];
  $timestamp = strtotime(str_replace("/", "-", $base_event["date"]));
  $x = $y = array();
   
  for ($i=1; $i < $length; $i++) {
    if (strtotime(str_replace("/", "-", $events[$i]["date"])) <= $timestamp) {
     $x[] = $events[$i];
    } else {
     $y[] = $events[$i];
    }
  }
  return array_merge(sortByDate($x), array($base_event), sortByDate($y));
}

function er_get_events_order_by ($events_data, $event, $orderBy) {  
  $key = "";
  $order_events = $events_data;

  switch ($orderBy) {
    case 'month':
      $event_date = explode("/", $event['date']);
      $key = $event_date[1]."/".$event_date[2];
      break;
    case 'championship':
      $key = str_replace(" ", "_", $event['championship']);
      break;
    default:
      break;
  }

  if (!isset($events_data[$key])) {
    $newArr = array(
      $key => array($event),
    );
    $order_events = array_merge($events_data, $newArr);
  } else {
    array_push($order_events[$key], $event);
  }
  
  return $order_events;
}


function er_get_events_by_championship () {
  $championship_id = isset($_POST['championship_id']) ? $_POST['championship_id'] : null;
  $search = isset($_POST['search']) ? $_POST['search'] : null;

  $post_type = 'er_evento';
  $tax_query = array();

  if (isset($championship_id)) {
    $tax_query = array(
      array(
        'taxonomy' => 'er_championship_cat',
        'field' => "term_id",
        'hide_empty' => true,
        'terms' => $championship_id
      )
    );
  } else {
    $tax_query = array(
      array(
        'taxonomy' => 'er_championship_cat',
        'field'    => 'name',
        'terms'    => $search,
        'operator' => 'IN'
      )
    );
  }
  
  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Obtener todos los posts del custom post type
    'orderby' => 'title',
    'order' => 'ASC',
    'tax_query' => $tax_query
  );

  $events = get_posts($args);
  $events_data = array();

  foreach($events as $event) {
    setup_postdata($event);
    if ($event->post_status == "publish") {
      $event_id = $event->ID;
      $track_id = get_field('er_track', $event_id);
      $date = get_field('er_event_date', $event_id);
      $splitedDate = explode("/", $date);
      $sameDate = array_diff($splitedDate, $dateFilter);
      $categories = get_the_terms($event, 'er_racer_category');
      $categories_name = array();
      $champioships = get_the_terms($event, 'er_championship_cat');
      $champioships_name = isset($champioships[0]) ? $champioships[0]->name : "";

      foreach($categories as $category_object) {
        array_push($categories_name, $category_object->name);
      }

      $event_data = array(
        'id' => $event_id,
        'title' => $event->post_title,
        'description' => get_field('er_event_description', $event_id),
        'image' => get_the_post_thumbnail_url($event_id),
        'date' => $date,
        'link' => get_permalink($event_id),
        'location' => get_field('er_track_location', $track_id),
        'length' => get_field('er_track_length', $track_id),
        'width' => get_field('er_track_width', $track_id),
        'curves' => get_field('er_track_curves', $track_id),
        'categories' => $categories_name,
        'championship' => $champioships_name,
      );

      $events_data = er_get_events_order_by($events_data, $event_data, "championship");
    }
  }

  foreach ($events_data as $key => $event) {
    $events_data[$key] = sortByDate($event);
  }
  $events_data = array_merge($events_data, array( "length" => count($events_data)));
  
  wp_reset_postdata(); // Restore post global object

  print_r(json_encode(array(
    "data" => $events_data,
  )));
  die();
}
add_action('wp_ajax_er_get_events_by_championship', 'er_get_events_by_championship');
add_action('wp_ajax_nopriv_er_get_events_by_championship', 'er_get_events_by_championship');


function er_get_event_filter () {
  $day = isset($_POST['day']) ? $_POST['day'] : null;
  $month = isset($_POST['month']) ? $_POST['month'] : null;
  $year = isset($_POST['year']) ? $_POST['year'] : null;
  $category = isset($_POST['category']) ? $_POST['category'] : 0;
  $orderBy = isset($_POST['orderBy']) ? $_POST['orderBy'] : null;
  $search = isset($_POST['search']) ? $_POST['search'] : null;
  $isRecent = isset($_POST['isRecent']) ? $_POST['isRecent'] : false;
  
  $dateFilter = [$day, $month, $year];
  $post_type = 'er_evento';
  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Obtener todos los posts del custom post type
    'category' => $category,
    'orderby' => 'title',
    'order' => 'ASC',
    's' => $search,
  );

  $events = get_posts($args);
  $events_data = array();
  $event_categories = array();

  foreach($events as $event) {
    setup_postdata($event);
    if ($event->post_status == "publish") {
      $event_id = $event->ID;
      $track_id = get_field('er_track', $event_id);
      $date = get_field('er_event_date', $event_id);
      $splitedDate = explode("/", $date);
      $sameDate = array_diff($splitedDate, $dateFilter);
      $categories = get_the_terms($event, 'er_racer_category');
      $categories_name = array();
      $champioships = get_the_terms($event, 'er_championship_cat');
      $champioships_name = isset($champioships[0]) ? $champioships[0]->name : "";

      foreach($categories as $category_object) {
        array_push($categories_name, $category_object->name);
      }

      $event_data = array(
        'id' => $event_id,
        'title' => $event->post_title,
        'description' => get_field('er_event_description', $event_id),
        'image' => get_the_post_thumbnail_url($event_id),
        'date' => $date,
        'link' => get_permalink($event_id),
        'location' => get_field('er_track_location', $track_id),
        'length' => get_field('er_track_length', $track_id),
        'width' => get_field('er_track_width', $track_id),
        'curves' => get_field('er_track_curves', $track_id),
        'categories' => $categories_name,
        'championship' => $champioships_name,
      );

      $isSearch = isset($search);
      $isCategory = $category != 0;
      $isDay = isset($day) && count($sameDate) === 0;
      $isMonth = !isset($day) && strcmp($splitedDate[1], $dateFilter[1]) == 0 && strcmp($splitedDate[2], $dateFilter[2]) == 0;
  
      if ($isSearch || $isCategory || $isDay || $isMonth) {
        array_push($events_data, $event_data);
      } else if (isset($orderBy)) {
        $events_data = er_get_events_order_by($events_data, $event_data, $orderBy);
      }
    }
  }

  if (isset($orderBy)) {
    foreach ($events_data as $key => $event) {
      $events_data[$key] = sortByDate($event);
    }
    $events_data = array_merge($events_data, array( "length" => count($events_data)));
  } else {
    $events_data = sortByDate($events_data);
  }

  wp_reset_postdata(); // Restore post global object

  print_r(json_encode(array(
    "data" => $events_data,
  )));
  die();
}
add_action('wp_ajax_er_get_event_filter', 'er_get_event_filter');
add_action('wp_ajax_nopriv_er_get_event_filter', 'er_get_event_filter');