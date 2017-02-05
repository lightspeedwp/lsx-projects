<?php
/**
 * LSX Projects Frontend Class
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link      
 * @copyright 2017 LightSpeed
 */
class LSX_Projects_Frontend {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_filter( 'template_include', array( $this, 'post_type_single_template_include'), 99 );
	}

	public function enqueue_style() {
        wp_enqueue_script( 'lsx_projects', LSX_PROJECTS_URL . 'assets/css/lsx-projects.css', array(), LSX_PROJECTS_VER, all );
	}
	
	public function post_type_single_template_include( $template ) {
        $template = LSX_PROJECTS_PATH . 'templates/full-projects.php';

		return $template;
	}

}

$lsx_projects_frontend = new LSX_Projects_Frontend();
