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
        wp_enqueue_script( 'lsx_projects', LSX_PROJECTS_URL . 'assets/css/lsx-projects.css', array(), LSX_PROJECTS_VER);
	}
	
	public function post_type_single_template_include( $template ) {

        if ( is_main_query() ){

            $r = $_SERVER['REQUEST_URI'];
            $r = explode('/', $r);
            $r = array_filter($r);
            $r = array_merge($r, array());
            $code = $r[0];

            if(is_singular( 'project' )){
                if ( '' == locate_template( array( 'single-project.php' ) ) && file_exists( LSX_PROJECTS_PATH . 'templates/single-project.php' ) ) {
                    $template = LSX_PROJECTS_PATH . 'templates/single-project.php';
                }
            }else if($code == 'project'){
                if ( '' == locate_template( array( 'full-projects.php' ) ) && file_exists( LSX_PROJECTS_PATH . 'templates/full-projects.php' ) ) {
                    $template = LSX_PROJECTS_PATH . 'templates/full-projects.php';
                }
            }


        }

		return $template;
	}

}

$lsx_projects_frontend = new LSX_Projects_Frontend();
