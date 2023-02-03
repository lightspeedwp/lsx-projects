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
