<?php
/*
 * Plugin Name: EYSS Racer
 * Plugin URI: https://eyss.io
 * Description: Karting event manager, track records, mechanics, drivers and results tables.
 * Version: 0.2.1
 * Author: EYSS
 * Author URI: https://eyss.io
 * Requires PHP: 8.1+
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
  exit;
}

!defined('ER_PATH') && define('ER_PATH', plugin_dir_path(__FILE__));
!defined('ER_URL') && define('ER_URL', plugin_dir_url(__FILE__));

require_once('modules/admin-config/config.php');
require_once('hooks/index.php');
require_once('templates/er-lists.php');
require_once('modules/er-events.php');  // Get Events by AJAX
require_once('assets/er-register-assets.php');     // Register assets functions
require_once('shortcode/er-short-codes.php'); // Shortcodes
