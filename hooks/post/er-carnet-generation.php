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
  // Filter posts
  if (strcmp('publish', $new_status) !== 0 ||
    (strcmp('er_racer', $post->post_type) !== 0 && strcmp('er_mechanic', $post->post_type) !== 0)
  ) return;

  $upload_dir = er_create_upload_path();
  $pdf_dir = $upload_dir . '/' . preg_replace('/\s+/', '', $post->post_title) . '.pdf';

  // Create an instance of the class:
  $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

  // Data to include into carnet
  $id = $post->ID;
  $avatar = get_the_post_thumbnail_url($id, 'full');
  $name = $post->post_title;
  $ci = '';
  $team = '';
  $state = '';
  $blood = '';
  $born = '';
  $allergic = '';
  $afiliate = '';

  // Mail data
  $mail_to = '';
  $subject = 'Felicidades! Su afiliación fue aprobada';
  $body = "Su afiliación fue aprobada con exito.\n\nAhora puede descargar su carnet de afiliación desde el archivo adjunto.\n\nEn caso de tener inconvenientes, contactenos al correo registro@fvkarting.com.ve";
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $attachments = array($pdf_dir);

  if (strcmp('er_racer', $post->post_type) === 0) {
    $mail_to = get_field('er_racer_email', $id);
    $ci = get_field('er_racer_id', $id);
    $team = get_field('er_karting_team', $id);
    $blood = get_field('er_racer_blood_type', $id);
    $state = get_field('er_racer_state_represented', $id);
    $born = get_field('er_racer_born_date', $id);
    $allergic = get_field('er_racer_allergic', $id);
    $afiliate = 'Piloto';
  } else if (strcmp('er_mechanic', $post->post_type) === 0) {
    $mail_to = get_field('er_mechanic_email', $id);
    $ci = get_field('er_mechanic_id', $id);
    $team = get_field('er_mechanic_team', $id);
    $blood = get_field('er_mechanic_blood_type', $id);
    $state = get_field('er_mechanic_team_site', $id);
    $born = get_field('er_mechanic_born_date', $id);
    $allergic = get_field('er_mechanic_allergic', $id);
    $afiliate = 'Mecánico';
  }

  if (strcmp($state, 'Distrito Capital') !== 0) {
    $state = 'Estado ' . $state;
  }

  $html = <<<_SC_HTML
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Carnet de Afiliación FVK</title>
    </head>
    <style>
      body {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        text-transform: uppercase;
      }

      .er-container-front, .er-container-back {
        border: 50px solid #500f12;
        border-width: 50px 0px;
        height: 800px;
        width: 500px;
        margin: auto;
        padding: 10px;
      }

      .er-header {
        text-align: center;
        font-size: 18px;
        color: #500f12;
      }

      .er-header img {
        margin: auto;
      }

      .er-header h1 {
        font-weight: 900;
      }

      .er-img-container {
        position: relative;
        height: 250px;
      }

      .er-qr-container {
        float: left;
        height: 250px;
        width: 200px;
      }

      .er-qr-container img {
        margin-top: 35px;
      }

      .er-avatar-container {
        float: right;
        height: 250px;
        width: 250px;
      }

      .er-avatar {
        width: 250px;
        height: 250px;
        border-radius: 200rem;
        background-image: url('$avatar');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        border: 10px solid #500f12;
      }

      .er-info {
        text-align: center;
        position: relative;
        margin-top: 40px;
        font-weight: 600;
      }

      .er-info .er-info-name {
        font-size: 56px;
        border-bottom: #500f12 solid 3px;
        width: 350px;
        margin: auto;
      }

      .er-info-ci, .er-info-team, .er-info-state, .er-afiliado {
        font-size: 32px;
        margin-top: 10px;
        text-align: center;
      }

      .er-afiliado {
        font-weight: 500;
        font-size: 32px;
        margin-top: 50px;
      }
    </style>
    <body>
      <div class="er-container-front">
        <div class="er-header">
          <img src="https://fvkarting.com.ve/wp-content/uploads/2023/03/LOGO-FVK-COLOR.png" width="120" alt="FVK">
          <h1>Federación Venezolana de Karting</h1>
        </div>

        <div class="er-img-container">
          <div class="er-qr-container">
            <img src="https://cdn.qr-code-generator.com/account29709329/qrcodes/72705600.png?Expires=1697000442&Signature=c7AJ7Io1gZoLJn2jqrxWaTVADxA7LZ56TaOSEtDi7N4tHVtXUN6qrQErWpqEqy4i02pNmYldPL0~6sF5elYR9dQmwAy74eSMFQfUrMtIIg1CoAp-voGz1bOjBEWVEYgVQxm1VWerMy7BhvowqAiyvNaUGaXXCEUtKEE6DZungJdnYi1BxJhWUj0wqLhyHvGLOUvSy0-HvhJUCRLDrT6mACWpYC8o9V1jmWytcr8CimenyCXoKFk-juD367~KOE-pPMEWjiWcEiKeTp9G1dAaTjk7BR0P8581C3Fqt4GJF1RbI3GryE7Pbc5~J7Ame-ZMpZJqM2SHVX5ak8pmWg9AHA__&Key-Pair-Id=KKMPOJU8AYATR" width="200" alt="QR">
          </div>
          <div class="er-avatar-container">
            <div class="er-avatar"></div>
          </div>
        </div>

        <div class="er-info">
          <div class="er-info-name">$name</div>
          <div class="er-info-ci">C.I: $ci</div>
          <div class="er-info-team">$team</div>
          <div class="er-info-state">$state</div>
        </div>

        <div class="er-afiliado">
          <span style="padding-right: 180px;">$afiliate Afiliado</span>
          <span>Nº$id</span>
        </div>
      </div>
    </body>
  </html>
  _SC_HTML;

  // Write some HTML code:
  $mpdf->WriteHTML($html);
  $mpdf->AddPage();

  $html = <<<_SC_HTML
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Carnet de Afiliación FVK</title>
    </head>
    <style>
      body {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        text-transform: uppercase;
      }
    
      .er-container-front, .er-container-back {
        border: 50px solid #500f12;
        border-width: 50px 0px;
        height: 800px;
        width: 500px;
        margin: auto;
        padding: 10px;
      }
    
      .er-header {
        text-align: center;
        font-size: 18px;
        color: #500f12;
      }
    
      .er-header img {
        margin: auto;
      }
    
      .er-header h1 {
        font-weight: 900;
      }
    
      .er-info {
        text-align: center;
        position: relative;
        margin-top: 40px;
        font-weight: 600;
      }
    
      .er-container-back {
        font-weight: 600;
      }
    
      .er-container-back .er-info, .er-info-permissions, .er-info-external, .er-president, .er-vencimiento, .er-contact {
        margin-top: 45px;
      }
    
      .er-container-back .er-info {
        font-size: 24px;
      }
    
      .er-info-external {
        width: 300px;
        margin: auto;
        text-align: center;
      }
    
      .er-president {
        text-align: center;
      }
    
      .er-vencimiento, .er-contact {
        text-align: center;
      }
    </style>
    <body>
      <div class="er-container-back">
        <div class="er-header">
          <img src="https://fvkarting.com.ve/wp-content/uploads/2023/03/LOGO-FVK-COLOR.png" width="200" alt="FVK">
        </div>

        <div class="er-info">
          <div>Grupo Sanguineo: $blood</div>
          <div>Fecha de Nacimiento: $born</div>
          <div>Alergico a: $allergic</div>
        </div>

        <ol class="er-info-permissions">
          <li>Este carnet identifica al portador como afiliado a la Federación Venezonala de Karting el mismo es personal e intransferible</li>
          <li>Este carnet no acredita autoridad ni porte de arma.</li>
          <li>En caso de requerir alguna información favor comunicarse con los siguientes números: (0426-447-25-04) o (0412-613-92-51)</li>
        </ol>

        <p class="er-info-external">Se agradece a las autoridades militares, civiles y deportivas le sean guardadas las debidas consideraciones al portador de este carnet para el fiel cumplimiento en la practica del deporte que representa.</p>

        <div class="er-president">
          <h2>Cooper Lopez</h2>
          <div>Presidente de la Federación Venezolana de Karting</div>
        </div>

        <p class="er-vencimiento">Fecha de Vencimiento: 25/09/2024</p>

        <h4 class="er-contact">https://fvkarting.com.ve</h4>
      </div>
    </body>
  </html>
  _SC_HTML;

  $mpdf->WriteHTML($html);

  // Output a PDF file directly to the browser
  $mpdf->OutputFile($pdf_dir);

  wp_mail($mail_to, $subject, $body, $headers, $attachments);
  wp_delete_file($pdf_dir);

  return;
}
