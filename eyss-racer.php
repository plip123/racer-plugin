<?php
/*
 * Plugin Name: EYSS Racer
 * Plugin URI: https://eyss.io
 * Description: Plugin 
 * Version: 0.0.1
 * Author: EYSS
 * Author URI: https://eyss.io
 * Requires PHP: 7.4+
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
  exit;
}

!defined('ER_PATH') && define('ER_PATH', plugin_dir_path(__FILE__));
!defined('ER_URL') && define('ER_URL', plugin_dir_url(__FILE__));

require_once('templates/er-lists.php');
require_once('modules/er-events.php');  // Get Events by AJAX
require_once('assets/er-register-assets.php');     // Register assets functions
//require_once('tools/er-generate-bd.php');     // Generate custom post types
require_once('shortcode/er-short-codes.php'); // Shortcodes