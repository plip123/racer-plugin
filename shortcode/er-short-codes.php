<?php

require_once(ER_PATH . 'templates/er-table.php');
require_once(ER_PATH. 'templates/er-event-widgets.php');
require_once(ER_PATH. 'modules/er-category-rank.php');

function er_notification (string $type, string $message) {
    echo "<script defer>print_notification('$type', '$message');</script>";
}

function er_category_rank () {
    $racers = er_category_rank_module();
    $html = "";
    foreach ($racers as $racer) {
        $innerhtml = er_category_table();
        $innerhtml = str_replace('%ER_CATEGORY%', strtolower($racer["category"]), $innerhtml);
        $innerhtml = str_replace('%ER_KART_NUMBER%', $racer["kart_number"], $innerhtml);
        $innerhtml = str_replace('%ER_RACER_NAME%', $racer["name"], $innerhtml);
        $innerhtml = str_replace('%ER_TEAM%', $racer["concurrent"], $innerhtml);
        $html .= $innerhtml;
    }
    
    return $html;
}
add_shortcode('er-category-rank', 'er_category_rank');

function er_position_list () {
    $data = array(
        array(
            'position' => 1,
            'kartnumber' => 123,
            'name' => 'Samuel Moreira',
            'dif1' => 0,
            'difAnt' => 0,
            'r1' => 65,
            'r2' => 75,
            'r3' => 150,
            'qlf' => 3,
            'm1' => 20
        )
    );
    $html = er_position_table($data);
    
    return $html;
}
add_shortcode('er-position-list', 'er_position_list');

function er_competition_events () {
    $html = er_short_event_template();
    $html = str_replace('%ER_EVENT_IMAGE%', "https://picsum.photos/200", $html);
    $html = str_replace('%ER_EVENT_NAME%', "kartódromo Carmencita Hernández", $html);
    $html = str_replace('%ER_EVENT_DATE%', "4 de marzo del 2023", $html);

    return $html;
}
add_shortcode('er-competition-events', 'er_competition_events');

function er_show_calendar_event () {
    return er_event_list_container(1);
}
add_shortcode('er-show-calendar-events', 'er_show_calendar_event');

function er_event_list () {
    return er_event_list_container();
}
add_shortcode('er-event-list', 'er_event_list');

function er_event_list_championship () {
    return er_event_list_container(0, 'championship');
}
add_shortcode('er-event-list-championship', 'er_event_list_championship');

//
// Widgets
//

function er_calendar_event () {
    return er_calendar_widget();
}
add_shortcode('er-calendar-widget', 'er_calendar_event');

function er_search_event_widget () {
    return er_search_widget("event");
}
add_shortcode('er-search-event-widget', 'er_search_event_widget');

function er_search_championship_widget () {
    return er_search_widget("championship");
}
add_shortcode('er-search-championship-widget', 'er_search_championship_widget');

function er_latest_championship_widget () {
    $args = array(
        'taxonomy'      => array('er_championship_cat'),
        'orderby'       => 'ID',
        'order'         => 'DESC',
        'hide_empty'    => true,
        'fields'        => 'all'
    );

    $terms = get_terms( $args );
    //print_r($terms);
    return er_latest_widget("championship", $terms);
}
add_shortcode('er-latest-championship-widget', 'er_latest_championship_widget');

