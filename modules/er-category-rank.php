<?php

function er_category_rank_module () {
  $post_type = 'er_racer';

  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Obtener todos los posts del custom post type
    'orderby' => 'title',
    'order' => 'ASC'
  );
  
  $posts = get_posts($args);
  $dataByRacer = array();

  foreach($posts as $post) {
    setup_postdata($post);
    $post_id = $post->ID;
    $category = get_field('er_category_id', $post_id);
    $data = array(
      "id" => $post->ID,
      "name" => $post->post_title,
      "kart_number" => get_field('er_racer_kart_number', $post_id),
      "concurrent" => get_field('er_racer_concurrent', $post_id),
      "category" => $category->name,
    );
    array_push($dataByRacer, $data);
  }

  wp_reset_postdata(); // Restore post global object

  return $dataByRacer;
}