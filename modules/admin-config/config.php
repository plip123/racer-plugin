<?php

add_action("admin_menu", "er_create_options");

function er_create_options() {
  add_submenu_page('er_evento', 'Resultados', 'Resultados', 'manage_options', 'er_results_menu', 'er_results_submenu');
}

function er_results_submenu () {
  ?>
    <h1>Authorization settings</h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=ecp_auth_menu&tab=register_options" class="nav-tab <?php echo $active_tab == 'register_options' ? 'nav-tab-active' : ''; ?>">Register</a>
        <a href="?page=ecp_auth_menu&tab=login_options" class="nav-tab <?php echo $active_tab == 'login_options' ? 'nav-tab-active' : ''; ?>">Login</a>
    </h2>
    <form method="post" action="options.php">
        <?php
            
            if( $active_tab == 'register_options' ) {
                //settings_fields( 'sandbox_theme_display_options' );
                //do_settings_sections( 'sandbox_theme_display_options' );
            } else {
                //settings_fields( 'ecp_integrations' );
                //do_settings_sections( 'ecp_integrations' );
            }
            
            submit_button();
            
        ?>
    </form>
    <?php
}