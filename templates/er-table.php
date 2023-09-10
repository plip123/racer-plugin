<?php
require_once(ER_PATH . "templates/er-generate-color.php");

function er_category_table () {
  $tag_color = erc_generate_random_color();

  $innerHtml = <<<_SC_HTML
    <div class="mb-11">
      <h5 class="text-sm font-bold mb-3.5">Categoría %ER_CATEGORY%</h5>
      <div class="w-full flex rounded-2xl overflow-hidden bg-white shadow-md h-14 items-center space-x-6">
        <span class="w-2.5 h-full bg-[$tag_color]"></span>
        <p class="text-right">Nro %ER_KART_NUMBER% / %ER_RACER_NAME% / %ER_TEAM%</p>
      </div>
    </div>
  _SC_HTML;

  return $innerHtml;
}

function er_position_table ($data) {
  $tableLayout = <<<_SC_HTML
    <table class="table-auto w-full text-center border-separate border-spacing-y-4">
      <thead>
        <tr>
          <th></th>
          <th>Pos.</th>
          <th>Nº</th>
          <th>Nombre</th>
          <th>Total</th>
          <th>Dif. resp. 1º</th>
          <th class="border-r-2 border-solid border-black">Dif. resp. anterior</th>
          <th class="w-10">R1</th>
          <th class="w-10">R2</th>
          <th class="w-10">R3</th>
          <th class="w-10">Qlf</th>
          <th class="w-10">M1</th>
        </tr>
      </thead>
      <tbody>
        %ER_BODY_POSITION_LIST%
      </tbody>
    </table>
  _SC_HTML;

  $tBody = '';

  foreach ($data as $key => $value) {
    $tag_color = erc_generate_random_color();
    $position = $key + 1;
    $kartNumber = $value['kartnumber'];
    $name = $value['name'];
    $dif1 = $value['dif1'];
    $difAnt = $value['difAnt'];
    $r1 = $value['r1'];
    $r2 = $value['r2'];
    $r3 = $value['r3'];
    $qlf = $value['qlf'];
    $m1 = $value['m1'];
    $total = $r1 + $r2 + $r3 + $qlf + $m1;

    $tBody .= <<<_SC_HTML
      <tr class="w-full overflow-hidden bg-white shadow-md h-14 mt-4 items-center">
        <td class="w-2.5 rounded-l-2xl h-full bg-[$tag_color]"></td>
        <td>$position</td>
        <td>$kartNumber</td>
        <td>$name</td>
        <td>$total</td>
        <td>$dif1</td>
        <td>$difAnt</td>
        <td>$r1</td>
        <td>$r2</td>
        <td>$r3</td>
        <td>$qlf</td>
        <td class="rounded-r-2xl">$m1</td>
      </tr>
    _SC_HTML;
  }

  $html = str_replace('%ER_BODY_POSITION_LIST%', $tBody, $tableLayout);
  
  return $html;
}