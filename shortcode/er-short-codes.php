<?php

require_once(ER_PATH . 'templates/er-table.php');
require_once(ER_PATH. 'templates/er-calendar-widget.php');
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
    $html = er_event_template();
    $html = str_replace('%ER_EVENT_IMAGE%', "https://picsum.photos/200", $html);
    $html = str_replace('%ER_EVENT_NAME%', "kartódromo Carmencita Hernández", $html);
    $html = str_replace('%ER_EVENT_DATE%', "4 de marzo del 2023", $html);
    $html = str_replace('%ER_EVENT_DESCRIPTION%', "El Kartódromo Carmencita Hernández albergará la primera valida del primer campeonato 2023.", $html);
    $html = str_replace('%ER_EVENT_UBICATION%', "Aragua, Venezuela.", $html);
    $html = str_replace('%ER_EVENT_LENGTH%', "1,08", $html);
    $html = str_replace('%ER_EVENT_WIDTH%', "8", $html);
    $html = str_replace('%ER_EVENT_CURVES%', "13", $html);
    $html = str_replace('%ER_EVENT_CATEGORIES%', "Baby, Mini novatos, Mini expertos, Junior, Senior, Master, Shifter.", $html);

    return $html;
}
add_shortcode('er-event-list', 'er_event_list');

function er_calendar_event () {
    $html = er_calendar_widget();

    return $html;
}
add_shortcode('er-calendar-widget', 'er_calendar_event');