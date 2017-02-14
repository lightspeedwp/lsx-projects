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
class LSX_Projects_Frontend
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('template_include', array($this, 'post_type_single_template_include'), 99);
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('lsx_projects', LSX_PROJECTS_URL . 'assets/css/lsx-projects.css', array(), LSX_PROJECTS_VER);
    }

    public function enqueue_scripts($plugins)
    {
        wp_enqueue_media();
        wp_enqueue_script('lsx_projects', LSX_PROJECTS_URL . 'assets/js/lsx-projects.js', array('jquery'),
            LSX_PROJECTS_VER);
    }

    public function post_type_single_template_include($template)
    {

        if (is_main_query()) {

            if (is_singular('project')) {
                if ('' == locate_template(array('single-project.php')) && file_exists(LSX_PROJECTS_PATH . 'templates/single-project.php')) {
                    $template = LSX_PROJECTS_PATH . 'templates/single-project.php';
                }
            } elseif(is_post_type_archive('project')) {
                if ('' == locate_template(array('full-projects.php')) && file_exists(LSX_PROJECTS_PATH . 'templates/full-projects.php')) {
                    $template = LSX_PROJECTS_PATH . 'templates/full-projects.php';
                }
            }

        }

        return $template;
    }

}

$lsx_projects_frontend = new LSX_Projects_Frontend();
