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

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function projects_get_options() {
	$options = array();
	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	// If there are new CMB2 options available, then use those.
	$new_options = get_option( 'lsx_projects_options', false );
	if ( false !== $new_options ) {
		$options['display'] = $new_options;
	}
	return $options;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function projects_get_option( $key = '', $default = false ) {
	$options = array();
	$value   = $default;
	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	// If there are new CMB2 options available, then use those.
	$new_options = get_option( 'lsx_projects_options', false );
	if ( false !== $new_options ) {
		$options['display'] = $new_options;
	}

	if ( isset( $options['display'] ) && isset( $options['display'][ $key ] ) ) {
		$value = $options['display'][ $key ];
	}
	return $value;
}


/**
 * Remove "Archives:"  from the projects archive title
 *
 * @param [type] $title
 * @return void
 */
function portfolio_modify_archive_title( $title ) {
	if ( ! is_post_type_archive( 'project' ) ) {
		return $title;
	}
	$title = __( 'Portfolio', 'lsx' );
	return $title;
}
add_filter( 'get_the_archive_title', 'portfolio_modify_archive_title', 10, 1 );
