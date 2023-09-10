<?php

function erc_generate_random_color () {
  $red = mt_rand(0, 255);
  $green = mt_rand(0, 255);
  $blue = mt_rand(0, 255);

  $color = "#" . dechex($red) . dechex($green) . dechex($blue);

  return $color;
}