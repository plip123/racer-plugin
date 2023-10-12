<?php

function er_get_front_carnet ($id, $avatar, $qr_svg_file, $name, $ci, $team, $state, $affiliate) {
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
        height: 850px;
        width: 550px;
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

      .er-qr-container {
        padding-top: 35px;
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
        margin-top: 10px;
        font-weight: 600;
      }

      .er-info .er-info-name {
        font-size: 30px;
        border-bottom: #500f12 solid 3px;
        width: 450px;
        margin: auto;
      }

      .er-info-ci, .er-info-team, .er-info-state, .er-afiliado {
        font-size: 24px;
        margin-top: 10px;
        text-align: center;
      }

      .er-afiliado {
        font-weight: 500;
        font-size: 28px;
        margin-top: 28px;
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
          <img src="$qr_svg_file" width="200" alt="QR">
          </div>
          <div class="er-avatar-container">
            <div class="er-avatar"></div>
          </div>
          <p></p>
        </div>

        <div class="er-info">
          <div class="er-info-name">$name</div>
          <div class="er-info-ci">C.I: $ci</div>
          <div class="er-info-team">$team</div>
          <div class="er-info-state">$state</div>
        </div>

        <div class="er-afiliado">
          <span style="padding-right: 180px;">$affiliate Afiliado</span>
          <span>Nº$id</span>
        </div>
      </div>
    </body>
  </html>
  _SC_HTML;

  return $html;
}

function er_get_back_carnet ($blood, $born, $allergic, $expiration_date) {
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
        height: 850px;
        width: 550px;
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
        font-size: 22px;
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

        <p class="er-vencimiento">Fecha de Vencimiento: $expiration_date</p>

        <h4 class="er-contact">https://fvkarting.com.ve</h4>
      </div>
    </body>
  </html>
  _SC_HTML;

  return $html;
}

function er_get_credential_letter ($id, $name, $ci, $expiration_date, $affiliate) {
  $html = <<<_SC_HTML
  
  _SC_HTML;

  return $html;
}