<?php
/**
 * Wraps the output class in a function to be called in templates
 */
function bs_project( $args ) {
	$bs_project = new BS_Project;
    echo $bs_project->output( $args );
}

// Compatibility with Homepage Control
add_action( 'homepage', 'bs_project', 10 );
