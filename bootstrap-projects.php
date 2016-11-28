<?php

/**
 * Plugin Name: Bootstrap Projects
 * Plugin URI: http://lsdev.biz
 * Description: Projects plugin for themes using the BootStrap Framework.
 * Author: Iain Coughtrie
 * Version: 1.05
 * Author URI: http://lsdev.biz
 */

// Post Type and Custom Fields
include plugin_dir_path( __FILE__ ) . '/inc/class-bs-projects-admin.php';

// Shortcode
include plugin_dir_path( __FILE__ ) . '/inc/class-bs-projects.php';

// Widget
include plugin_dir_path( __FILE__ ) . '/inc/class-bs-projects-widget.php';

// Settings
// include plugin_dir_path( __FILE__ ) . '/inc/class-bs-projects-settings.php';

// Template Tag and functions
include plugin_dir_path( __FILE__ ) . '/inc/bs-projects-functions.php';
