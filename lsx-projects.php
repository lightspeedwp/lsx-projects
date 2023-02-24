<?php
/*
 * Plugin Name: LSX Projects
 * Plugin URI:  https://www.lsdev.biz/product/lsx-projects/
 * Description: The LSX Projects extension adds the "Projects" post type.
 * Version:     2.0.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-projects
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_PROJECTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_PROJECTS_CORE', __FILE__ );
define( 'LSX_PROJECTS_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_PROJECTS_VER', '2.0.0' );

/* ======================= Below is the Plugin Class init ========================= */
// Project Core.
require_once LSX_PROJECTS_PATH . '/includes/classes/class-core.php';
LSX\Projects\Classes\Core::get_instance();
