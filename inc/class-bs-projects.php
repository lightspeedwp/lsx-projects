<?php

class BS_Project {

    public $options;

    public function __construct()
    {
        $this->options = get_option('bs_project_options');

        // if ( $this->options['disable_single'] ) 
        //     add_action( 'template_redirect', array($this, 'disable_single' ) );

        add_shortcode( 'projects', array($this, 'output' ) );        
    }

    /**
     * Removes access to single project  posts
     */
    public function disable_single() 
    {
        $queried_post_type = get_query_var('post_type');
        if ( is_single() && 'project' ==  $queried_post_type ) {
            wp_redirect( home_url(), 301 );
            exit;
        }
    }    

    /**
     * Returns the shortcode output markup
     */
    public function output( $atts ) 
    {
        extract( shortcode_atts( array(
            'columns' => 3,
            'orderby' => 'name',
            'order' => 'ASC',
            'limit' => '-1',
            'group' => '',
            'include' => '',
            'size' => 320,
        ), $atts ) );
        
        $output = "";   

        if ( $include != '' ) {
        	$include = explode( ',', $include );
        	$args = array(
        			'post_type' => 'project',
        			'feature-group' => $group,
        			'posts_per_page' => $limit,
        			'post__in' => $include,
        			'orderby' => 'post__in',
        			'order' => $order
        	);
        } else {
       		$args = array(
               'post_type' => 'project',
               'project_group' => $group,
               'posts_per_page' => $limit,
               'orderby' => $orderby,
               'order' => $order                 
            );
        }        
       

        $projects = get_posts( $args );
        
        if ( !empty( $projects ) ) {            
            $count = 0;
            if ( $columns >= 1 && $columns <= 4 )
                $output .= "<div class='bs-projects row'>";

            foreach ( $projects as $project ) {
                
                // Vars
                $count++;
                if ( has_post_thumbnail( $project->ID ) ) {
                    $image = get_the_post_thumbnail( $project->ID, array( $size, $size ), 'class=img-responsive' );
                } else {
                    $image = "<img src='http://placehold.it/320x213/' alt='placeholder' class='img-responsive' />";
                }
                $content = $project->post_excerpt;
                $link_open = "<a href='" . get_permalink( $project->ID ) . "'>";
                $link_close = "</a>";
                $title = "<h3>$link_open $project->post_title $link_close</h3>";                            

                // Output
                if ( $columns >= 1 && $columns <= 4 ) {

                    $md_col_width = intval( 12/$columns );

                    $output .= "
                    <div class='col-md-$md_col_width col-xs-12 bs-project'>
                        <div class='well'>                         
                            $link_open $image $link_close                                                        
                            $title                       
                            $content                            
                        </div>
                    </div>
                    ";

                    if ( $count%$columns == 0 ) $output .= "<div class='clearfix'></div>";

                } else {

                    $output .= "
                        <p class='bg-warning' style='padding: 20px;'>
                            Invalid number of columns set. Bootstrap Project s supports 1, 2, 3 or 4 columns.
                        </p>";
                    return $output;

                };

            }
            if ( $columns >= 1 && $columns <= 4 )
                $output .= "</div>";
        return $output;
        }
    }
    
}
 
$BS_Project = new BS_Project();