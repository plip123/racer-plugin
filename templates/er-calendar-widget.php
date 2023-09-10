<?php

function er_calendar_widget () {
  $html = <<<_SC_HTML
    <div id="er-wrapper">
      <div id="er-calendar" class="bg-white rounded-2xl shadow-md text-[#313131]"></div>
    </div>
  _SC_HTML;

  return $html;
}