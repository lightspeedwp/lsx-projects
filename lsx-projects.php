<?php
/**
 * Plugin Name: LSX Projects
 * Plugin URI: http://lsdev.biz
 * Description: Projects plugin for themes using the BootStrap Framework.
 * Author: Iain Coughtrie
 * Version: 1.05
 * Author URI: http://lsdev.biz
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'LSX_PROJECTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_PROJECTS_CORE', __FILE__ );
define( 'LSX_PROJECTS_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_PROJECTS_VER', '1.0.0' );

// Post Type and Custom Fields
include LSX_PROJECTS_PATH . '/classes/class-projects-admin.php';

// Shortcode
include LSX_PROJECTS_PATH . '/classes/class-projects.php';

// Widget
include LSX_PROJECTS_PATH . '/classes/class-projects-widget.php';

// Settings
include LSX_PROJECTS_PATH . '/classes/class-projects-settings.php';

// Settings
include LSX_PROJECTS_PATH . '/classes/class-projects-frontend.php';

// Template Tag and functions
include LSX_PROJECTS_PATH . '/includes/projects-functions.php';

// Post reorder
require_once( LSX_PROJECTS_PATH . '/includes/post-order.php' );