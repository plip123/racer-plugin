<?php

function er_race_category_taxonomy () {
  $taxonomy_name = 'race_category';
  $post_type = 'racer';

  if (taxonomy_exists($taxonomy_name)) {
    unregister_taxonomy($taxonomy_name);
  }

  $labels = array(
    'name'                       => __( 'Categorías de Karting', 'text-domain' ),
    'singular_name'              => __( 'Categoría de Karting', 'text-domain' ),
    'search_items'               => __( 'Buscar Categorías de Karting', 'text-domain' ),
    'popular_items'              => __( 'Categorías de Karting Populares', 'text-domain' ),
    'all_items'                  => __( 'Todas las Categorías de Karting', 'text-domain' ),
    'parent_item'                => __( 'Categoría de Karting Padre', 'text-domain' ),
    'parent_item_colon'          => __( 'Categoría de Karting Padre:', 'text-domain' ),
    'edit_item'                  => __( 'Editar Categoría de Karting', 'text-domain' ),
    'update_item'                => __( 'Actualizar Categoría de Karting', 'text-domain' ),
    'add_new_item'               => __( 'Agregar Nueva Categoría de Karting', 'text-domain' ),
    'new_item_name'              => __( 'Nombre de la Nueva Categoría de Karting', 'text-domain' ),
    'separate_items_with_commas' => __( 'Separar las Categorías de Karting con comas', 'text-domain' ),
    'add_or_remove_items'        => __( 'Agregar o Eliminar Categorías de Karting', 'text-domain' ),
    'choose_from_most_used'      => __( 'Elegir de las Categorías de Karting más utilizadas', 'text-domain' ),
    'not_found'                  => __( 'No se encontraron Categorías de Karting.', 'text-domain' ),
    'menu_name'                  => __( 'Categorías de Karting', 'text-domain' ),
  );

  $args = array(
      'labels'                     => $labels,
      'hierarchical'               => false,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'rewrite' => array( 'slug' => 'eventos' ),
      'meta_box_cb' => 'post_categories_meta_box_radio',
  );

  register_taxonomy($taxonomy, $post_type, $args );
}
// add_action('init', 'er_race_category_taxonomy');

function er_events_table () {
  $post_type = 'events';

  if (post_type_exists($post_type)) {
    unregister_post_type($post_type);
  }

  $labels = array(
    'name'               => __( 'Eventos' ),
    'singular_name'      => __( 'Evento' ),
    'add_new'            => __( 'Agregar Nuevo Evento' ),
    'add_new_item'       => __( 'Agregar Nuevo Evento' ),
    'edit_item'          => __( 'Editar Evento' ),
    'new_item'           => __( 'Nuevo Evento' ),
    'all_items'          => __( 'Todos los Eventos' ),
    'view_item'          => __( 'Ver Evento' ),
    'search_items'       => __( 'Buscar Eventos' ),
    'featured_image'     => 'Imagen',
    'set_featured_image' => 'Agregar Imagen'
  );
  
  $args = array(
      'labels'      => $labels,
      'public'      => true,
      'has_archive' => true,
      'rewrite'     => array( 'slug' => 'evento' ),
      'menu_icon'   => 'dashicons-calendar-alt',
      'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
  );

  register_post_type($post_type, $args);
  
  // global $wpdb;
  // $prefix = $wpdb->prefix;
  // $collate = $wpdb->collate;
  // $table_name = $prefix.'er_events';

  // $sql = "CREATE TABLE $table_name (
  //   id bigint(20) NOT NULL AUTO_INCREMENT,
  //   name varchar(255) NOT NULL,
  //   description varchar(500),
  //   type varchar(20),
  //   date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  //   location varchar(255) NOT NULL,
  //   length decimal(8),
  //   width decimal(8),
  //   curves int(4),
  //   category_ids text NOT NULL,
  //   PRIMARY KEY (id)
  // ) COLLATE {$collate}";

}
// add_action('init', 'er_events_table');

function er_register_custom_fields() {
  // RACER
  $post_type = 'racer';

  // Valida a correr
  register_meta($post_type, 'er_racer_valid', array(
      'type' => 'string',
      'description' => 'Valida a correr',
      'single' => true,
      'show_in_rest' => true,
  ));

  // Name
  register_meta($post_type, 'er_racer_name', array(
    'type' => 'string',
    'description' => 'Nombre del Piloto',
    'single' => true,
    'show_in_rest' => true,
  ));

  // Concurrente
  register_meta($post_type, 'er_racer_concurrent', array(
    'type' => 'string',
    'description' => 'Concurrente',
    'single' => true,
    'show_in_rest' => true,
  ));

  // Kart number
  register_meta($post_type, 'er_racer_kart_number', array(
    'type' => 'string',
    'description' => 'Número de Kart',
    'single' => true,
    'show_in_rest' => true,
  ));
}

function er_pay_table () {
  global $wpdb;
  $prefix = $wpdb->prefix;
  $collate = $wpdb->collate;
  $table_name = $prefix.'er_pay';

  $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    check boolean DEFAULT 0,
    amount decimal(8) NOT NULL,
    method varchar(20) NOT NULL,
    ref int(4) NOT NULL,
    proof bigint(20) NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$collate}";
}
// add_action('init', 'er_pays_table');

function er_racers_post_type () {
  $post_type = 'racer';

  if (post_type_exists($post_type)) {
    unregister_post_type($post_type);
  }

  $labels = array(
    'name'               => __( 'Pilotos' ),
    'singular_name'      => __( 'Piloto' ),
    'add_new'            => __( 'Agregar Nuevo Piloto' ),
    'add_new_item'       => __( 'Agregar Nuevo Piloto' ),
    'edit_item'          => __( 'Editar Piloto' ),
    'new_item'           => __( 'Nuevo Piloto' ),
    'all_items'          => __( 'Todos los Pilotos' ),
    'view_item'          => __( 'Ver Piloto' ),
    'search_items'       => __( 'Buscar Pilotos' ),
    'featured_image'     => 'Avatar',
    'set_featured_image' => 'Agregar Avatar'
  );
  
  $args = array(
      'labels'      => $labels,
      'public'      => true,
      'has_archive' => true,
      'rewrite'     => array( 'slug' => 'piloto' ),
      'menu_icon'   => 'dashicons-car',
      'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
      'taxonomies' => array('category'),
  );

  register_post_type($post_type, $args);
  //er_register_custom_fields();

  // global $wpdb;
  // $prefix = $wpdb->prefix;
  // $collate = $wpdb->collate;
  // $table_name = $prefix.'er_racer';

  // $sql = "CREATE TABLE $table_name (
  //   id bigint(20) NOT NULL AUTO_INCREMENT,
  //   name varchar(20) NOT NULL,
  //   valid int(4) NOT NULL,
  //   concurrent varchar(200) NOT NULL,
  //   kartnumber int(8) NOT NULL, 
  //   amount decimal(8),
  //   pits_stand varchar(20),
  //   pay_id varchar(20),
  //   category_id bigint(20) NOT NULL,
  //   PRIMARY KEY (id)
  // ) COLLATE {$collate}";
}
// add_action('init', 'er_racers_post_type');
