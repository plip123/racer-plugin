<?php

function er_calendar_widget () {
  $html = <<<_SC_HTML
    <div id="er-wrapper">
      <div id="er-calendar" class="bg-white rounded-2xl shadow-md text-[#313131]"></div>
    </div>
  _SC_HTML;
  return $html;
}

function er_search_widget ($post_type) {
  $placeholder = "";

  switch ($post_type) {
    case 'event':
      $placeholder = "evento";
      break;
    case 'pilot':
      $placeholder = "piloto";
      break;
  }

  $html = <<<_SC_HTML
    <div id="er-search" class="bg-white rounded-2xl shadow-md text-[#9B9BA7]">
      <input id="er-search-$post_type" type="search" placeholder="Buscar $placeholder" class="bg-white w-full h-14 p-8 rounded-2xl">
    </div>
  _SC_HTML;
  return $html;
}

function er_latest_widget ($post_type, $posts) {
  $tag = array();
  switch ($post_type) {
    case 'event':
      $tag = array(
        "single" => "evento",
        "plural" => "eventos"
      );
      break;
    case 'championship':
      $tag = array(
        "single" => "campeonato",
        "plural" => "campeonatos"
      );
      break;
    case 'pilot':
      $tag = array(
        "single" => "piloto",
        "plural" => "pilotos"
      );
      break;
  }

  $html_posts_list = <<<_SC_HTML
    <div class="text-center mx-5 p-4 er-alert text-[#FFFF] bg-["#dc3545"]/[.6]">
      No hay eventos por el momento.
    </div>
  _SC_HTML;

  if (count($posts) > 0) {
    $html_posts_list = <<<_SC_HTML
      <div id="er-latest-all" class="border-b py-5 cursor-pointer" onClick="er_get_post_by_id(-1, '$post_type')">
        Todos los {$tag["plural"]}
      </div>
    _SC_HTML;

    foreach ($posts as $key => $post) {
      $html_posts_list .= <<<_SC_HTML
        <div id="er-$post_type-{$post->term_taxonomy_id}" class="border-b text-base py-5 cursor-pointer" onClick="er_get_post_by_id({$post->term_taxonomy_id}, '$post_type')">
          {$post->name}
        </div>
      _SC_HTML;
    }
  }
  
  $html = <<<_SC_HTML
    <div id="er-latest-$post_type" class="bg-white rounded-2xl shadow-md text-[#424141] px-4 py-8">
      <h5 class="font-bold text-xl capitalize">{$tag["plural"]}</h5>
      $html_posts_list
    </div>
  _SC_HTML;

  return $html;
}