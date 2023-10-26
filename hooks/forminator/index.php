<?php

add_filter('forminator_custom_form_submit_errors', 'er_custom_form_validation', 10, 3);

function er_custom_form_validation($submit_errors, $form_id, $field_data_array) {
  $post_type = '';
  $id_field_name = '';
  $register_type = '';
  $email = '';
  $confirm_email = '';
  $email_err = '';

  // Skip if is not the correct form
  switch ((int) $form_id) {
    case 604:
      $email = $_POST['email-1'];
      $confirm_email = $_POST['email-3'];
      $email_err = 'email-3';

      $post_type = 'er_racer';
      $id_field_name = 'er_racer_id';
      $register_type = 'piloto';
      break;
    case 603:
      $email = $_POST['email-1'];
      $confirm_email = $_POST['email-2'];
      $email_err = 'email-2';

      $post_type = 'er_mechanic';
      $id_field_name = 'er_mechanic_id';
      $register_type = 'mecánico';
      break;
    default:
      return $submit_errors;
  }

  if (strcmp($email, $confirm_email) !== 0) {
    $submit_errors[][$email_err] = __( 'El correo debe coincidir.' );
    return $submit_errors;
  }

  if ((int) $form_id === 604 && (int) $_POST['number-6'] < 18 && strcmp($_POST['email-2'], $_POST['email-4']) !== 0) {
    $submit_errors[]['email-4'] = __( 'El correo debe coincidir.' );
    return $submit_errors;
  }

  $pilot_id = strval($_POST['number-7']);
  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Obtener todos los posts del custom post type
    'orderby' => 'title',
    'order' => 'ASC',
    'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'pay-pending'),
  );
  $pilots = get_posts($args);

  foreach ($pilots as $pilot) {
    $id = get_field($id_field_name, $pilot->ID);

    if (strcmp($id, $pilot_id) === 0) {
      $submit_errors[]['number-7'] = __( 'Este '.$register_type.' ya fue registrado' );
    }
  }

  // Always return $submit_errors
  return $submit_errors;

}
