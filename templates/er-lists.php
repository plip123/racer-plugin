<?php

function er_short_event_template () {
  $html = <<<_SC_HTML
    <div class="flex items-center rounded-2xl w-full overflow-hidden bg-white shadow-md max-h-[190px]">
      <image class="object-cover w-4/12 h-full" src="%ER_EVENT_IMAGE%" alt="%ER_EVENT_NAME%">

      <div class="flex flex-col gap-2 text-base p-5">
        <h5 class="font-bold">%ER_EVENT_NAME%</h5>
        <p><span class="dashicons dashicons-calendar-alt"></span> %ER_EVENT_DATE%</p>
        <span id="show-categories" class="hidden cursor-pointer opacity-70">Ver categorías compitiendo</span>
        <span id="about-event" class="cursor-pointer opacity-70">Ver más del evento</span>
      </div>
    </div>
  _SC_HTML;

  return $html;
}

function er_event_template () {
  $html = <<<_SC_HTML
    <div class="flex rounded-2xl w-full overflow-hidden bg-white shadow-md max-h-[330px]">
      <image class="object-cover w-4/12 h-full" src="%ER_EVENT_IMAGE%" alt="%ER_EVENT_NAME%">

      <div class="flex flex-col gap-2 text-base p-5">
        <h5 class="font-bold">%ER_EVENT_NAME%</h5>
        <p><span class="dashicons dashicons-calendar-alt"></span> %ER_EVENT_DATE%</p>
        <p>%ER_EVENT_DESCRIPTION%</p>
        <p><span class="font-bold">Ubicación</span> %ER_EVENT_UBICATION%</p>
        <p><span class="font-bold">Longitud</span> %ER_EVENT_LENGTH% km</p>
        <p><span class="font-bold">Ancho</span> %ER_EVENT_WIDTH% m</p>
        <p><span class="font-bold">Curvas</span> %ER_EVENT_CURVES%</p>
        <p><span class="font-bold">Categorías compitiendo</span> %ER_EVENT_CATEGORIES%</p>
      </div>
    </div>
  _SC_HTML;

  return $html;
}


function er_event_list_container ($max_events=0) {
  $id = $max_events == 0 ? "er-event-list" : "er-show-event";

  $html = <<<_SC_HTML
      <div id="$id" class="flex flex-col gap-8"></div>
  _SC_HTML;

  return $html;
}