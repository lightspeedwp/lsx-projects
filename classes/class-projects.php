<?php

class LSX_Project {

    public $options;

    public function __construct()
    {
        $this->options = get_option('project_options');

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
        			'order' => $order,
                    'post_name' => $atts['post_name']
        	);
        } else {
       		$args = array(
                'post_type' => 'project',
                'project_group' => $group,
                'posts_per_page' => $limit,
                'orderby' => $orderby,
                'order' => $order,
                'post_name' => $atts['post_name']
            );
        }

        if(is_single()){
            $project = get_post();

            if ( !empty( $project ) ) {

                if ( has_post_thumbnail( $project->ID ) ) {
                    $image = get_the_post_thumbnail( $project->ID, array( $size, $size ), 'class=img-responsive' );
                } else {
                    $image = "<img src='http://placehold.it/730x250/' alt='placeholder' class='img-responsive' />";
                }
                $content = $project->post_excerpt;
                $link_open = "<a href='" . get_permalink( $project->ID ) . "'>";
                $link_close = "</a>";

                $subtitle = get_the_terms($project->ID,'project_group');

                $title = $subtitle[0]->name;
                $title .= "<h3>$link_open $project->post_title $link_close</h3>";

                $output = "
                    <div class='bs-project row'>
                        <div class='well'>                         
                            $link_open $image $link_close                                                        
                            $title                       
                            $content                            
                        </div>
                    </div>
                    ";

                return $output;
            }

        }else{
            $projects = get_posts( $args );

            if ( !empty( $projects ) ) {
            $count = 0;
            if ( $columns >= 1 && $columns <= 4 )
                $output .= "<div class=\"filter-items-wrapper lsx-portfolio-wrapper\">
                                <div id=\"portfolio-infinite-scroll-wrapper\" class=\"filter-items-container lsx-portfolio masonry\">";

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

                $subtitle = get_the_terms($project->ID,'project_group');
                $title = $project->post_title;

                // Output
                if ( $columns >= 1 && $columns <= 4 ) {

                    $md_col_width = intval( 12/$columns );

                    $output .= "
                    <article id=\"post-24527\" data-column=\"3\" class=\"filter-item column-3 ".str_replace(' ', '',$subtitle[0]->name) ."\">
                        <div class=\"portfolio-content-wrapper\">
                            <div class=\"portfolio-thumbnail\">
                                $link_open<!-- a href=\"https://www.lsdev.biz/portfolio/run-it-off/\" -->
                                    $image
                                    <!-- img class=\"attachment-responsive wp-post-image lsx-responsive\" srcset=\"https://www.lsdev.biz/wp-content/uploads/2016/10/pexels-photo-65305-350x230.jpeg\" scale=\"0\">				</a -->
                                $link_close
                            </div>
            
                            <a class=\"portfolio-title\" href=". get_permalink( $project->ID ) ." rel=\"bookmark\" style=\"margin-top: -14px;\"><span>$title</span></a>	</div>
                    </article>
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
                $output .= "</div>
                                <br clear=\"all\">
                            </div>";

            return $output;
            }
        }
    }

    /**
     * Returns the shortcode output markup
     */
    public function groups( )
    {

        $args = [
            'taxonomy' => 'project_group',
//            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'asc'
        ];

        $data = get_terms($args);

        if ( !empty( $data ) ) {

            $output .= "<div class='bs-projects row'>
                            <ul id=\"filterNav\" class=\"clearfix\"'>
                              <li class='allBtn'><a href=\"#\" data-filter=\"*\" class=\"selected\">All</a></li>";
            foreach ( $data as $return ) {
//                echo "<pre>";var_dump($return);exit;
                $output .= "<li><a href=\"#\" data-filter=\".".str_replace(' ', '', $return->name)."\" class=\"\">$return->name</a></li>";
            }
            $output .= "</ul></div>";

        return $output;
        }
    }

}


$LSX_Project = new LSX_Project();