<?php

declare(strict_types=1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require_once ER_PATH . '/vendor/autoload.php';
require_once ER_PATH . '/templates/er-affiliate-credentials.php';
require_once ER_PATH . '/tools/er-upload-path.php';


function er_get_qr ($id, $name, $ci, $team, $blood, $born, $allergic) {
  $message = "Afiliado Nº $id, \r\nName: $name, \r\nC.I: $ci, \r\nFecha de nacimiento: $born, \r\nEquipo: $team, \r\nTipo de Sangre: $blood, \r\nAlergico a: $allergic";
  $options = new QROptions(
    [
      'eccLevel' => QRCode::ECC_L,
      'outputType' => QRCode::OUTPUT_MARKUP_SVG,
      'version' => QRCode::VERSION_AUTO,
    ]
  );
  $qrcode = (new QRCode($options))->render($message);

  return $qrcode;
}

add_action( 'transition_post_status', 'er_new_post_user', 10, 3 );

function er_new_post_user( $new_status, $old_status, $post ) {
  // Filter posts
  $isPublished = strcmp('publish', $new_status) === 0;
  if (!$isPublished ||
    (strcmp('er_racer', $post->post_type) !== 0 && strcmp('er_mechanic', $post->post_type) !== 0)
  ) return;

  $upload_dir = er_create_upload_path();
  $carnet_dir = $upload_dir . '/' . preg_replace('/\s+/', '', $post->post_title) . '-carnet.pdf';
  $affiliate_credential_dir = $upload_dir . '/' . preg_replace('/\s+/', '', $post->post_title) . '-credencial.pdf';

  // Data to include into carnet
  $id = $post->ID;
  $avatar = get_the_post_thumbnail_url($id, 'full');
  $name = $post->post_title;
  $ci = '';
  $genere = '';
  $team = '';
  $state = '';
  $blood = '';
  $born = '';
  $allergic = '';
  $affiliate = '';
  $expiration_year = intval(get_the_date('Y', $id)) + 1; // Publish year + 1
  $expiration_date = get_the_date('d/m/', $id) . $expiration_year; // dd/mm/yyyy

  // Mail data
  $mail_to = '';
  $subject = 'Felicidades! Su afiliación fue aprobada';
  $body = "Su afiliación fue aprobada con exito.<br><br>Ahora puede descargar sus credenciales de afiliación desde los archivos adjuntos.<br><br>En caso de tener inconvenientes contactenos al correo registro@fvkarting.com.ve";
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $attachments = array($carnet_dir, $affiliate_credential_dir);

  if (strcmp('er_racer', $post->post_type) === 0) {
    $mail_to = get_field('er_racer_email', $id);
    $ci = get_field('er_racer_id', $id);
    $genere = get_field('er_racer_genere', $id);
    $team = get_field('er_karting_team', $id);
    $blood = get_field('er_racer_blood_type', $id);
    $state = get_field('er_racer_state_represented', $id);
    $born = get_field('er_racer_born_date', $id);
    $allergic = get_field('er_racer_allergic', $id);
    $affiliate = 'Piloto';

    if (strcmp($state, 'Distrito Capital') !== 0) {
      $state = 'Estado ' . $state;
    }
  } else if (strcmp('er_mechanic', $post->post_type) === 0) {
    $mail_to = get_field('er_mechanic_email', $id);
    $ci = get_field('er_mechanic_id', $id);
    $genere = get_field('er_mechanic_genere', $id);
    $team = get_field('er_mechanic_team', $id);
    $blood = get_field('er_mechanic_blood_type', $id);
    $state = get_field('er_mechanic_team_site', $id);
    $born = get_field('er_mechanic_born_date', $id);
    $allergic = get_field('er_mechanic_allergic', $id);
    $affiliate = 'Mecánico';
  }

  $ci = number_format(intval($ci), 0, ',', '.');

  // Create QR
  $qr_image = er_get_qr($id, $name, $ci, $team, $blood, $born, $allergic);

  if (!isset($qr_image)) {
    $body = "Su afiliación fue aprobada con exito, pero hubo un error al generar sus credenciales de afiliación, por favor coloquese en contacto con nosotros mediante registro@fvkarting.com.ve";
  }

  // Create an instance of the class:
  $mpdf = new \Mpdf\Mpdf([
    'format' => [150, 235],
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0,
  ]);

  // Write some HTML code:
  $html = er_get_front_carnet($id, $avatar, $qr_image, $name, $ci, $team, $state, $affiliate);
  $mpdf->WriteHTML($html);
  $mpdf->AddPage();

  $html = er_get_back_carnet($blood, $born, $allergic, $expiration_date);
  $mpdf->WriteHTML($html);

  // Output a PDF file to temporal dir
  $mpdf->OutputFile($carnet_dir);

  // Create Credential Letter
  $mpdf = new \Mpdf\Mpdf();

  $html = er_get_credential_letter($id, $name, $ci, $expiration_date, $affiliate, $genere, $qr_image);
  $mpdf->WriteHTML($html);

  // Output a PDF file to temporal dir
  $mpdf->OutputFile($affiliate_credential_dir);

  // Send email
  wp_mail($mail_to, $subject, $body, $headers, $attachments);

  // Clear temporal files
  wp_delete_file($carnet_dir);
  wp_delete_file($affiliate_credential_dir);

  return;
}
