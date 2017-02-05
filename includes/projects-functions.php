<?php
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