<?php

function er_get_registration_form ($data) {
  $html = <<<_SC_HTML
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Planilla de Revisión Técnica FVK</title>
    </head>
    <style>
      .er-text-center {
        text-align: center;
      }

      .er-uppercase {
        text-transform: uppercase;
      }

      .er-bold {
        font-weight: bold;
      }

      .er-header {
        margin: auto;
        height: 75px;
      }

      .er-header .er-logo {
        float: left;
        width: 100px;
        padding-top: 20px;
      }

      .er-header .er-qr-code {
        float: right;
        height: 100px;
        width: 100px;
      }

      .er-main-title {
        margin-top: 5px;
        margin-bottom: 5px;
      }

      .er-main-content {
        line-height: 20px;
      }

      .er-container {
        padding-left: 10px;
      }

      .er-section-title {
        width: 100%;
        color: white;
        background-color: gray;
      }

      .er-section-title, h5 {
        margin: 0;
      }

      .er-col {
        width: 50%;
        font-size: 12px;
      }

      .er-col-left {
        float: left;
      }

      .er-col-right {
        float: right;
      }

      hr {
        width: 0;
      }
    </style>
    <body>
      <div class="er-header er-text-center">
        <div class="er-logo">
          <img src="https://fvkarting.com.ve/wp-content/uploads/2023/03/LOGO-FVK-COLOR.png" width="100" alt="FVK">
        </div>
      </div>

      <h5 class="er-main-title er-uppercase er-text-center">Planilla de revisión Técnica<br>{$data['event_name']} {$data['event_date']}</h5>

      <div class="er-main-content">
        <div>
          <h5 class="er-uppercase er-text-center er-section-title">Datos del Piloto</h5>

          <div class="er-container">
            <div class="er-col er-col-left">
              <span class="er-uppercase er-bold">Nombre Piloto:</span> {$data['name']} <br>
              <span class="er-uppercase er-bold">Concurrente:</span> {$data['concurrente']}
            </div>
            <div class="er-col er-col-right">
              <span class="er-uppercase er-bold">Categoría:</span> {$data['category']} <br>
              <span class="er-uppercase er-bold">Número Kart:</span> {$data['kart_number']}
            </div>
          </div>
        </div>

        <hr>

        <div>
          <h5 class="er-uppercase er-text-center er-section-title">Inscripción Técnica</h5>
          <div class="er-container">
            <div class="er-col er-col-left">
              <div class="er-uppercase er-text-center">Chasis</div>
              <span class="er-uppercase er-bold">Años Chasis 1:</span> {$data['chasis_year_1']} <br>
              <span class="er-uppercase er-bold">Años Chasis 2:</span> {$data['chasis_year_2']} <br>
              <span class="er-uppercase er-bold">Marca Chasis 1:</span> {$data['chasis_marca_1']} <br>
              <span class="er-uppercase er-bold">Marca Chasis 2:</span> {$data['chasis_marca_2']} <br>
              <span class="er-uppercase er-bold">Serial Chasis 1:</span> {$data['chasis_serial_1']} <br>
              <span class="er-uppercase er-bold">Serial Chasis 2:</span> {$data['chasis_serial_2']} <br>
              <span class="er-uppercase er-bold">Homologación:</span> {$data['homologacion']} <br>
              <span class="er-uppercase er-bold">Nombre Mecánico:</span> {$data['chasis_mechanic']}
            </div>
            <div class="er-col er-col-right">
              <div class="er-uppercase er-text-center">Motores</div>
              <span class="er-uppercase er-bold">Cilindrada:</span> {$data['cilindrada']} <br>
              <span class="er-uppercase er-bold">Marca Motor 1:</span> {$data['motor_marca_1']} <br>
              <span class="er-uppercase er-bold">Serial Motor 1:</span> {$data['motor_serial_1']} <br>
              <span class="er-uppercase er-bold">Marca Motor 2:</span> {$data['motor_marca_2']} <br>
              <span class="er-uppercase er-bold">Serial Motor 2:</span> {$data['motor_serial_2']} <br>
              <span class="er-uppercase er-bold">Técnico Motorista:</span> {$data['motor_tecnico']} <br>
              <span class="er-uppercase er-bold">No. Carnet T.M.:</span> {$data['motor_tecnico_number']}
            </div>
          </div>
        </div>

        <br>
        <hr>
        <br>

        <div>
          <h5 class="er-uppercase er-text-center er-section-title">Cauchos</h5>
          <div class="er-container">
            <div class="er-col er-col-left">
              <div class="er-uppercase er-text-center">Slick</div>
              <span class="er-uppercase er-bold">Serial 1:</span> {$data['cauchos_slick_1']} <br>
              <span class="er-uppercase er-bold">Serial 2:</span> {$data['cauchos_slick_2']} <br>
              <span class="er-uppercase er-bold">Serial 3:</span> {$data['cauchos_slick_3']} <br>
              <span class="er-uppercase er-bold">Serial 4:</span> {$data['cauchos_slick_4']}
            </div>
            <div class="er-col er-col-right">
              <div class="er-uppercase er-text-center">Lluvia</div>
              <span class="er-uppercase er-bold">Serial 1:</span> {$data['cauchos_lluvia_1']} <br>
              <span class="er-uppercase er-bold">Serial 2:</span> {$data['cauchos_lluvia_2']} <br>
              <span class="er-uppercase er-bold">Serial 3:</span> {$data['cauchos_lluvia_3']} <br>
              <span class="er-uppercase er-bold">Serial 4:</span> {$data['cauchos_lluvia_4']}
            </div>
          </div>
        </div>

        <hr>

        <div>
          <h5 class="er-uppercase er-text-center er-section-title">¿El Kart lleva cámara instalada?: {$data['has_camera']}</h5>
        </div>

        <hr>

        <div>
          <h5 class="er-uppercase er-text-center er-section-title">Revisión Técnica</h5>
          <div class="er-container">
            <div class="er-col er-col-left">
              <span class="er-uppercase er-bold"># Precinto Motor 1:</span> {$data['precinto_motor_1']} <br>
              <span class="er-uppercase er-bold"># Precinto Motor 2:</span> {$data['precinto_motor_2']} <br>
              <span class="er-uppercase er-bold">Asiento:</span> {$data['asiento']} <br>
              <span class="er-uppercase er-bold">Ponton Delantero:</span> {$data['ponton_delantero']} <br>
              <span class="er-uppercase er-bold">Pontones Laterales:</span> {$data['pontones_laterales']} <br>
              <span class="er-uppercase er-bold">Parachoques Trasero:</span> {$data['parachoques_trasero']} <br>
              <span class="er-uppercase er-bold">Volante:</span> {$data['volante']} <br>
              <span class="er-uppercase er-bold">Mycron:</span> {$data['mycrone']} <br>
              <span class="er-uppercase er-bold">Batería:</span> {$data['bateria']} <br>
              <span class="er-uppercase er-bold">Dirección:</span> {$data['direccion']}
            </div>
            <div class="er-col er-col-right">
              <span class="er-uppercase er-bold">Frenos Delanteros:</span> {$data['frenos_delanteros']} <br>
              <span class="er-uppercase er-bold">Frenos Traseros:</span> {$data['frenos_traseros']} <br>
              <span class="er-uppercase er-bold">Plomos:</span> {$data['plomos']} <br>
              <span class="er-uppercase er-bold">Piso Kart:</span> {$data['piso_kart']} <br>
              <span class="er-uppercase er-bold">Tank Combustible:</span> {$data['tanque_combustible']} <br>
              <span class="er-uppercase er-bold">Contenedores:</span> {$data['contenedores']} <br>
              <span class="er-uppercase er-bold">Líquidos Visibles:</span> {$data['liquidos_visibles']} <br>
              <span class="er-uppercase er-bold">Instalación Elec:</span> {$data['instalacion_electrica']} <br>
              <span class="er-uppercase er-bold">Relación Baby:</span> {$data['relacion_baby']} <br>
              <span class="er-uppercase er-bold">Encendido/Arranque:</span> {$data['encendido_arranque']} <br>
            </div>
          </div>
        </div>

        <hr>
        <br>

        <div>
          <div class="er-container">
            <div class="er-col er-col-left">
              <span class="er-uppercase er-bold">Nombre Dir Técnico 1:</span> <br>
              <span class="er-uppercase er-bold">Nombre Dir Técnico 3:</span>
            </div>
            <div class="er-col er-col-right">
              <span class="er-uppercase er-bold">Nombre Dir Técnico 4:</span> <br>
              <span class="er-uppercase er-bold">Aprobado:</span>
            </div>
          </div>
        </div>

        <hr>

        <h5 class="er-uppercase er-text-center er-section-title">
          Nota: Es obligatorio completar todos los campos (planilla incompleta no será aceptada)
        </h5>
      </div>
      
    </body>
  </html>
  _SC_HTML;

  return $html;
}