<?php
/**
 * Functions
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */

/**
 * Add our action to init to set up our vars first.
 */
function lsx_projects_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-projects', false, basename( LSX_PROJECTS_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_projects_load_plugin_textdomain' );

/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_projects( $args ) {
	$lsx_projects = new LSX_Projects;
	echo wp_kses_post( $lsx_projects->output( $args ) );
}

/**
 * Shortcode
 */
function lsx_projects_shortcode( $atts ) {
	$lsx_projects = new LSX_Projects;
	return $lsx_projects->output( $atts );
}
add_shortcode( 'lsx_projects', 'lsx_projects_shortcode' );

/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_groups_list() {
	do_action( 'lsx_groups_list' );
}

function lsx_child_group_list() {
	do_action( 'lsx_child_group_list' );
}

function lsx_projects_list() {
	do_action( 'lsx_projects_list' );
}

function lsx_projects_sidebar() {
	do_action( 'lsx_projects_sidebar' );
}

function lsx_projects_single_tag() {
	do_action( 'lsx_projects_single_tag' );
}
