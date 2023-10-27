<?php

function er_create_upload_path () {
  $upload = wp_upload_dir();
  $upload_dir = trailingslashit($upload['basedir']) . '/fvk-racer';
  if (!is_dir($upload_dir)) wp_mkdir_p($upload_dir);

  return $upload_dir;
}