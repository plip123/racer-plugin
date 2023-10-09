<?php

require_once ER_PATH . '/vendor/autoload.php';

function er_create_upload_path () {
  $upload = wp_upload_dir();
  $upload_dir = trailingslashit($upload['basedir']) . '/er_racer';
  if (!is_dir($upload_dir)) wp_mkdir_p($upload_dir);

  return $upload_dir;
}

add_action( 'transition_post_status', 'er_new_post_user', 10, 3 );

function er_new_post_user( $new_status, $old_status, $post ) {
  if (strcmp('publish', $new_status) !== 0) return;

  $upload_dir = er_create_upload_path();
  $pdf_dir = $upload_dir . '/' . preg_replace('/\s+/', '', $post->post_title) . '.pdf';

  // Create an instance of the class:
  $mpdf = new \Mpdf\Mpdf();

  // Write some HTML code:
  $mpdf->WriteHTML('<h1>Hello world!</h1>');

  // Output a PDF file directly to the browser
  $mpdf->OutputFile($pdf_dir);

  if (strcmp('er_racer', $post->post_type) === 0) {
    $mail_to = get_field('er_racer_email', $post->ID);
    $subject = 'Email subject';
    $body = 'Email body content';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachments = array(); //array($pdf_dir);
    //wp_mail($mail_to,$subject,$body,$headers,$attachments);
    wp_mail('cpservice98@gmail.com', 'Piloto', 'Mensaje Piloto');
  } else if (strcmp('er_mechanic', $post->post_type) === 0) {
    $mail_to = get_field('er_mechanic_email', $post->ID);
    $subject = 'Email subject';
    $body = 'Email body content';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachments = array(); //array($pdf_dir);
    //wp_mail('cpservice98@gmail.com',$subject,$body,$headers,$attachments);
    wp_mail('cpservice98@gmail.com', 'Mecanico', 'Mensaje Mecanico');
  } else {
    wp_mail('cpservice98@gmail.com', 'NADA', 'Mensaje');
    return;
  }

  return;
}
