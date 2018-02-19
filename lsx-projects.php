<?php
/*
 * Plugin Name: LSX Projects
 * Plugin URI:  https://www.lsdev.biz/product/lsx-projects/
 * Description: The LSX Projects extension adds the "Projects" post type.
 * Version:     1.1.1
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
define( 'LSX_PROJECTS_VER', '1.1.1' );

/* ======================= Below is the Plugin Class init ========================= */

// Post Type and Custom Fields
require_once( LSX_PROJECTS_PATH . '/classes/class-lsx-projects-admin.php' );

// Frontend scripts and styles
require_once( LSX_PROJECTS_PATH . '/classes/class-lsx-projects-frontend.php' );

// Shortcode and Template Tag
require_once( LSX_PROJECTS_PATH . '/classes/class-lsx-projects.php' );

// Widget
require_once( LSX_PROJECTS_PATH . '/classes/class-lsx-projects-widget.php' );

// Template Tag and functions
require_once( LSX_PROJECTS_PATH . '/includes/functions.php' );

// Post reorder
require_once( LSX_PROJECTS_PATH . '/includes/class-lsx-projects-scpo-engine.php' );
