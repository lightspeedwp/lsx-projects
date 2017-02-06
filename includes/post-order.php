<?php
 
$lsx_projects_scporder = new LSX_PROJECTS_SCPO_Engine();

/**
 * SCPO Engine
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link      
 * @copyright 2016 LightSpeed
 */
class LSX_PROJECTS_SCPO_Engine
{

    function __construct()
    {
        add_action('lsx_groups_list', array($this, 'lsx_groups'));
        add_action('lsx_child_group_list', array($this, 'lsx_groups'));
        add_action('lsx_projects_list', array($this, 'lsx_projects'));
    }

    function lsx_groups()
    {
        $bs_project = new LSX_Project;
        $output = $bs_project->groups();
        echo $output;
    }

    function lsx_child_groups($args)
    {
        $args = [];
        $output = child_group($args);
        echo $output;
    }

    function lsx_projects()
    {
        $args = [];

        $r = $_SERVER['REQUEST_URI'];
        $r = explode('/', $r);
        $r = array_filter($r);
        $r = array_merge($r, array());
        $length = count($r);
        $code = $r[$length - 1];
        if($code !== 'projects'){
            $args = [
              'post_name' => $code
            ];
        }

        $bs_project = new LSX_Project;
        $output = $bs_project->output($args);
        echo $output;
    }

}
