<?php

// CSS

function er_styles_enqueue_styles() {
    wp_register_style('er-calendar-styles', plugins_url('/css/calendar/mini-event-calendar.min.css', __FILE__), '1', true);
    wp_enqueue_style('er-calendar-styles');

    wp_register_style('er-styles', plugins_url('/css/index.css', __FILE__), '1', true);
    wp_enqueue_style('er-styles');
}
add_action( 'wp_enqueue_scripts', 'er_styles_enqueue_styles',10);


function er_js_insert_enqueue() {
  // Tailwind
  wp_register_script('er-tailwind-script', plugins_url('/js/tailwind/tailwind.js', __FILE__), array(), '3.3.1', true);
  wp_enqueue_script( 'er-tailwind-script');

  // Calendar
  wp_register_script('er-calendar-script', plugins_url('/js/calendar/mini-event-calendar.min.js', __FILE__), array(), '3.3.1', true);
  wp_enqueue_script('er-calendar-script');

  wp_register_script('er-calendar-data-script', plugins_url('/js/calendar/er-calendar-data.js', __FILE__), array('jquery'), '1', true);
  wp_enqueue_script( 'er-calendar-data-script');
  wp_localize_script( 'er-calendar-data-script','er_calendar_ajax', ['ajaxurl'=>admin_url('admin-ajax.php')]);

  wp_register_script('er-event-script', plugins_url('/js/events.js', __FILE__), array(), '3.3.1', true);
  wp_enqueue_script('er-event-script');
}

add_action('wp_enqueue_scripts', 'er_js_insert_enqueue');