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
  $mpdf = new \Mpdf\Mpdf();

  $html = <<<_SC_HTML
  <style>
    body {
      display: flex;
      flex-direction: column;
      gap: 80px;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      text-transform: uppercase;
    }

    .er-container-front, .er-container-back {
      border: 50px solid #500f12;
      border-width: 50px 0px;
      max-height: 800px;
      max-width: 500px;
      height: 800px;
      width: 100%;
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
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .er-qr-container {
      height: 100%;
      width: 40%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .er-avatar-container {
      height: 100%;
      width: 60%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .er-avatar {
      width: 250px;
      height: 250px;
      object-fit: cover;
      border-radius: 50%;
      border: 10px solid #500f12;
    }

    .er-info {
      text-align: center;
      display: flex;
      flex-direction: column;
      margin-top: 40px;
      font-weight: 600;
    }

    .er-info .er-info-name {
      font-size: 56px;
      border-bottom: #500f12 solid 3px;
      width: 80%;
      margin: auto;
    }

    .er-info-ci, .er-info-team, .er-info-state, .er-afiliado {
      font-size: 32px;
      margin-top: 10px;
      text-align: center;
    }

    .er-afiliado {
      display: flex;
      gap: 120px;
      justify-content: center;
      font-weight: 500;
      font-size: 32px;
      margin-top: 30px;
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
          <img src="https://cdn.qr-code-generator.com/account29709329/qrcodes/72705600.png?Expires=1696910753&Signature=rCE3vwekjp0WaZFsR5Icg2HhxnIUSOU3jrpNLnUb-7zGPpIhy-F8eXyY5fXGW1Vomsjeb9mYHQi9eDltDgkp2mTBGpaAwHgdRkpiUTuxfZk~8ADP7tPOvQTSzeYStfJNO-hMoy9M~fcNGZFy1YJaX~x3Ca~N-zC2ceTlcIGbVbRNp8VrddY~kMjXEcpThY0Ny64dGsHAn5MXjbJvLvt~0hV3hXwPUJLg6IOlY39c3OSgJI5k96xPIOcs6xcJsxeBvPK8FomVUs3DutCcPefE0si4JPRj8GgDPMTnV73cY-i2JF~JgNihwBMkb7uUL~pUAuKO7zWPiVdQEREFzPsTMg__&Key-Pair-Id=KKMPOJU8AYATR" width="200" alt="QR">
        </div>
        <div class="er-avatar-container">
          <img class="er-avatar" src="https://fvkarting.com.ve/wp-content/uploads/2023/09/8F297442-A2DA-4D53-9D6F-32D43A3931C2-scaled.jpeg" width="250" height="250" alt="Avatar">
        </div>
      </div>

      <div class="er-info">
        <span class="er-info-name">Carlos Pino</span>
        <span class="er-info-ci">C.I: 26.484.045</span>
        <span class="er-info-team">Team</span>
        <span class="er-info-state">Estado Carabobo</span>
      </div>

      <div class="er-afiliado">
        <span>Piloto Afiliado</span>
        <span>Nº001</span>
      </div>
    </div>
  </body>
  _SC_HTML;

  // Write some HTML code:
  $mpdf->WriteHTML($html);
  $mpdf->AddPage('L');

  $html = <<<_SC_HTML
    <style>
      body {
        display: flex;
        flex-direction: column;
        gap: 80px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        text-transform: uppercase;
      }

      .er-container-front, .er-container-back {
        border: 50px solid #500f12;
        border-width: 50px 0px;
        max-height: 800px;
        max-width: 500px;
        height: 800px;
        width: 100%;
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
        display: flex;
        flex-direction: column;
        margin-top: 40px;
        font-weight: 600;
      }

      .er-container-back {
        font-weight: 600;
      }

      .er-container-back .er-info, .er-info-permissions, .er-info-external, .er-president, .er-vencimiento, .er-contact {
        margin-top: 60px;
      }

      .er-container-back .er-info {
        font-size: 24px;
      }

      .er-info-external {
        width: 60%;
        margin: auto;
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
          <span>Grupo Sanguineo: O+</span>
          <span>Fecha de Nacimiento: 27/10/1993</span>
          <span>Alergico a: Nueces</span>
        </div>

        <ol class="er-info-permissions">
          <li>Este carnet identifica al portador como afiliado a la Federación Venezonala de Karting el mismo es personal e intransferible</li>
          <li>Este carnet no acredita autoridad ni porte de arma.</li>
          <li>En caso de requerir alguna información favor comunicarse con los siguientes números: (0426-447-25-04) o (0412-613-92-51)</li>
        </ol>

        <p class="er-info-external">Se agradece a las autoridades militares, civiles y deportivas le sean guardadas las debidas consideraciones al portador de este carnet para el fiel cumplimiento en la practica del deporte que representa.</p>

        <div class="er-president">
          <span>Cooper Lopez</span>
          <span>Presidente de la Federación Venezolana de Karting</span>
        </div>

        <p class="er-vencimiento">Fecha de Vencimiento: 25/09/2024</p>

        <h4 class="er-contact">https://fvkarting.com.ve</h4>
      </div>
    </body>
  _SC_HTML;
  $mpdf->WriteHTML($html);

  // Output a PDF file directly to the browser
  $mpdf->OutputFile($pdf_dir);

  // Mail data
  $mail_to = '';
  $subject = 'Felicidades! Su afiliación fue aprobada';
  $body = 'Email body content';
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $attachments = array($pdf_dir);

  if (strcmp('er_racer', $post->post_type) === 0) {
    $mail_to = get_field('er_racer_email', $post->ID);
  } else if (strcmp('er_mechanic', $post->post_type) === 0) {
    $mail_to = get_field('er_mechanic_email', $post->ID);
  }

  wp_mail($mail_to, $subject, $body, $headers, $attachments);
  wp_delete_file($pdf_dir);

  return;
}
