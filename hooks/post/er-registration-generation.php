<?php

require_once ER_PATH . '/vendor/autoload.php';
require_once ER_PATH . '/templates/er-registration.php';
require_once ER_PATH . '/tools/er-upload-path.php';

add_action( 'transition_post_status', 'er_new_registration', 10, 3 );

function er_new_registration( $new_status, $old_status, $post ) {
  // Filter posts
  $isPublished = (strcmp('publish', $new_status) === 0);
  if (!$isPublished || strcmp('inscripcion', $post->post_type) !== 0) return;

  
  $upload_dir = er_create_upload_path();
  $revision_dir = $upload_dir . '/' . preg_replace('/\s+/', '', $post->post_title) . '-revision-tecnica.pdf';
  $id = $post->ID;
  $pilot_id = get_field('er_registration_pilot', $id);
  $event = get_post(get_field('er_registration_event', $id));
  $event_name = $event->post_title;
  $event_date = get_field('er_event_date', $event->ID);
  
  // Mail data
  $mail_to = array(get_field('er_racer_email', $pilot_id));//, 'cp.carlos.pino@gmail.com');
  $subject = 'Felicidades! Su inscripción al evento ' . $event_name . ' fue aprobada';
  $body = "Su inscripción al evento <strong>" . $event_name . "</strong> de la fecha <strong>" . $event_date . "</strong> fue aprobada con exito.<br><br>Ahora puede descargar <strong>PLANILLA DE REVISIÓN TÉCNICA</strong> desde los archivos adjuntos.<br><br>En caso de tener inconvenientes contactenos al correo registro@fvkarting.com.ve";
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $attachments = array($revision_dir);

  $registration_data = array(
    // Event Data
    'event_name' => $event_name,
    'event_date' => $event_date,

    // Pilot Data
    'pilot_id' => $pilot_id,
    'name' => $post->post_title,
    'category' => get_field('er_registration_category', $id),
    'concurrente' => get_field('er_registration_concurrente', $id),
    'kart_number' => get_field('er_karting_kart_number', $pilot_id),
  

    // Inscripcion tecnica

    // Chasis
    'chasis_year_1' => get_field('er_check_chasis_date_1', $id),
    'chasis_year_2' => get_field('er_check_chasis_date_2', $id),
    'chasis_marca_1' => get_field('er_check_chasis_1', $id),
    'chasis_marca_2' => get_field('er_check_chasis_2', $id),
    'chasis_serial_1' => get_field('er_check_chasis_serial_1', $id),
    'chasis_serial_2' => get_field('er_check_chasis_serial_2', $id),
    'homologacion' => get_field('er_check_chasis_homologacion', $id),
    'chasis_mechanic' => get_field('er_check_chasis_mechanic', $id),

    // Motores
    'cilindrada' => get_field('er_check_motor_cilindrada', $id),
    'motor_marca_1' => get_field('er_check_motor_1', $id),
    'motor_serial_1' => get_field('er_check_motor_serial_1', $id),
    'motor_marca_2' => get_field('er_check_motor_2', $id),
    'motor_serial_2' => get_field('er_check_motor_serial_2', $id),
    'motor_tecnico' => get_field('er_check_motor_tecnico', $id),
    'motor_tecnico_number' => get_field('er_check_motor_tecnico_number', $id),


    // Cauchos

    // Slick
    'cauchos_slick_1' => get_field('er_check_cauchos_slick_1', $id),
    'cauchos_slick_2' => get_field('er_check_cauchos_slick_2', $id),
    'cauchos_slick_3' => get_field('er_check_cauchos_slick_3', $id),
    'cauchos_slick_4' => get_field('er_check_cauchos_slick_4', $id),

    // Lluvia
    'cauchos_lluvia_1' => get_field('er_check_cauchos_lluvia_1', $id),
    'cauchos_lluvia_2' => get_field('er_check_cauchos_lluvia_2', $id),
    'cauchos_lluvia_3' => get_field('er_check_cauchos_lluvia_3', $id),
    'cauchos_lluvia_4' => get_field('er_check_cauchos_lluvia_4', $id),


    // Camera
    'has_camera' => get_field('er_check_camera', $id),


    // Revisión Tecnica general
    'precinto_motor_1' => get_field('er_check_general_precinto_motor_1', $id),
    'precinto_motor_2' => get_field('er_check_general_precinto_motor_2', $id),
    'asiento' => get_field('er_check_general_asiento', $id),
    'ponton_delantero' => get_field('er_check_general_ponton_delantero', $id),
    'pontones_laterales' => get_field('er_check_general_pontones_laterales', $id),
    'parachoques_trasero' => get_field('er_check_general_parachoques_trasero', $id),
    'volante' => get_field('er_check_general_volante', $id),
    'mycrone' => get_field('er_check_general_mycrone', $id),
    'bateria' => get_field('er_check_general_bateria', $id),
    'direccion' => get_field('er_check_general_direccion', $id),
    'frenos_delanteros' => get_field('er_check_general_frenos_delanteros', $id),
    'frenos_traseros' => get_field('er_check_general_frenos_traseros', $id),
    'plomos' => get_field('er_check_general_plomos', $id),
    'piso_kart' => get_field('er_general_check_piso_kart', $id),
    'tanque_combustible' => get_field('er_check_general_tanque_de_combustible', $id),
    'contenedores' => get_field('er_check_general_contenedores', $id),
    'liquidos_visibles' => get_field('er_check_general_liquidos_visibles', $id),
    'instalacion_electrica' => get_field('er_check_general_instalacion_electrica', $id),
    'relacion_baby' => get_field('er_check_general_relacion_baby', $id),
    'encendido_arranque' => get_field('er_check_general_encendido_arranque', $id)
  );

  // Create check form
  $mpdf = new \Mpdf\Mpdf();

  $html = er_get_registration_form($registration_data);
  $mpdf->WriteHTML($html);

  // Output a PDF file to temporal dir$mpdf->OutputFile($revision_dir);
  $mpdf->OutputFile($revision_dir);

  // Send email
  wp_mail($mail_to, $subject, $body, $headers, $attachments);

  // Clear temporal files
  wp_delete_file($revision_dir);

  return;
}