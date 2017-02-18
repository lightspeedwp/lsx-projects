<?php

class LSX_Project
{

    public $options;

    public function __construct()
    {
        $this->options = get_option('project_options');

        // if ( $this->options['disable_single'] ) 
        //     add_action( 'template_redirect', array($this, 'disable_single' ) );

        add_shortcode('projects', array($this, 'output'));
        add_filter( 'lsx_banner_allowed_post_types', array( $this, 'lsx_banner_allow_post_type' ) );
        add_filter( 'lsx_banner_enable_placeholder', array( $this, 'lsx_banner_disable' ) );
        add_action( 'wp', array( $this, 'lsx_banner_change' ) );
        add_action( 'lsx_content_wrap_before', array( $this, 'lsx_banner_edit' ) );
    }

    /**
     * Enable project custom post type on LSX Banners
     */
    public function lsx_banner_allow_post_type( $post_types )
    {
        $post_types[] = 'project';
        return $post_types;
    }

    /**
     * Show button to disable banner
     */
    function lsx_banner_disable( $boolean ) {
        if ( is_post_type_archive( 'project' ) ) {
            return false;
        } elseif ( is_admin() ) {
            //check if is single in admin
            return true;
        }

        return $boolean;
    }

    /**
     * Remove global header and set lsx banner
     */
    function lsx_banner_change() {
        if ( is_post_type_archive( 'project' ) ) {
            remove_action( 'lsx_content_wrap_before', 'lsx_global_header' );
        }
    }

    /**
     * Edit global header
     */
    function lsx_banner_edit() {
        if ( is_post_type_archive( 'project' ) ) {
            //set configs to get the featured image

            $posts = get_posts([
                'post_type' => 'project'
            ]);

            $count = 0;
            foreach ($posts as $post){
                $meta = get_post_meta($post->ID);

                if($meta['project_featured'][0] == 1){
                    $count = 1;
                    $title = $post->post_title;
                    $sub = get_the_terms($post->ID, 'project_group');
                    $subtitle = $sub[0]->name;
                    $image = get_the_post_thumbnail_url($post->ID, array('1200px','600px'));
                }
            }

            if($count > 0){
            ?>
            <header class="archive-header" style="height: 400px; background: transparent; z-index: 1">
                <p>Featured project:</p>
                <h1 class="page-title"><?=$title?></h1>
                <p><?=$subtitle?></p>
            </header>
            <header class="archive-header" style="
                height: 405px;
                background-size: contain;
                position: absolute;
                top: 0;
                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                filter: blur(5px);
                z-index: -1;
                background-image: url('<?=$image?>')">;
            </header>
            <?php
            }else{
                ?>
                <header class="archive-header">
                    <h1 class="archive-title">Portfolio</h1>
                </header>
                <?php
            }
        }
    }

    /**
     * Removes access to single project  posts
     */
    public function disable_single()
    {
        $queried_post_type = get_query_var('post_type');
        if (is_single() && 'project' == $queried_post_type) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }

    /**
     * Returns the shortcode output markup
     */
    public function output($atts)
    {

        extract(shortcode_atts(array(
            'columns' => 3,
            'orderby' => 'name',
            'order' => 'ASC',
            'limit' => '-1',
            'group' => '',
            'include' => '',
            'size' => 320,
        ), $atts));

        $output = "";

        if ($include != '') {
            $include = explode(',', $include);
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

        if (is_single()) {
            $project = get_post();

            if (!empty($project)) {

                $output = '
                    <div class="lsxp-context-section">'
                    . $project->post_content .
                    '</div>';

                return $output;
            }

        } else {
            $projects = get_posts($args);

            if (!empty($projects)) {
                $count = 0;
                if ($columns >= 1 && $columns <= 4) {
                    $output .= "<div class='filter-items-wrapper lsx-portfolio-wrapper'>
                                <div id='portfolio-infinite-scroll-wrapper' class='filter-items-container lsx-portfolio masonry'>";
                }

                foreach ($projects as $project) {
                    // Vars
                    $count++;
                    if (has_post_thumbnail($project->ID)) {
                        $image = get_the_post_thumbnail($project->ID, array('480px', '320'),
                            'class=img-responsive project-image');
                    } else {
                        $image = "<img src='http://placehold.it/480x320/' alt='placeholder' class='img-responsive project-image' />";
                    }
                    $content = $project->post_excerpt;
                    $link_open = "<a href='" . get_permalink($project->ID) . "'>";
                    $link_close = "</a>";

                    $subtitle = get_the_terms($project->ID, 'project_group');
                    $title = $project->post_title;

                    if ($this->options['show_groups'] == 1) {
                        $title = $subtitle[0]->name . "<br/><bold style='color:#525252;font-weight: bold;'>$title</bold>";
                    } else {
                        $title = "<bold style='
                                        color: #525252;
                                        font-weight: bold;
                                        float: left;
                                        width: 100%;
                                        margin-top: 20px;
                                            '>$title</bold>";
                    }

                    // Output
                    if ($columns >= 1 && $columns <= 4) {

                        $md_col_width = intval(12 / $columns);
                        $class = str_replace(' ', '', $subtitle[0]->name);
                        $output .= "
                    <article data-column='3' class='filter-item column-3 $class'>
                        <div class='projects-thumbnail'>
                            $link_open $image $link_close
                            <span class='projects-thumbnail-text' 
                               style='
                                    background: #f2f2f2;
                                    width: 100%;
                                    float: left;
                                    padding: 10px;
                                    height: 100px;
                                    text-align: center;
                                    border-bottom-left-radius: 5px;
                                    border-bottom-right-radius: 5px;
                               '>
                                $title
                            </span>
                        </div>
                    </article>
                    ";

                        if ($count % $columns == 0) {
                            $output .= "<div class='clearfix'></div>";
                        }

                    } else {

                        $output .= "
                        <p class='bg-warning' style='padding: 20px;'>
                            Invalid number of columns set. Bootstrap Project s supports 1, 2, 3 or 4 columns.
                        </p>";
                        return $output;

                    };

                }
                if ($columns >= 1 && $columns <= 4) {
                    $output .= "</div>
                                <br clear=\"all\">
                            </div>";
                }

                return $output;
            }
        }
    }

    /**
     * Returns the shortcode output markup
     */
    public function groups()
    {

        if ($this->options['show_groups'] == 1) {
            $args = [
                'taxonomy' => 'project_group',
//            'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'asc'
            ];

            $data = get_terms($args);

            if (!empty($data)) {

                $output .= "<div class='bs-projects row'>
                            <ul id=\"filterNav\" class=\"clearfix\"'>
                              <li class='allBtn'><a href=\"#\" data-filter=\"*\" class=\"selected\">All</a></li>";
                foreach ($data as $return) {
                    $output .= "<li><a href=\"#\" data-filter=\"." . str_replace(' ', '',
                            $return->name) . "\" class=\"\">$return->name</a></li>";
                }
                $output .= "</ul></div>";
            }


            return $output;
        }
    }

    public function sidebar()
    {

        $project = get_post();

        $meta = get_post_meta($project->ID);
        $client = $meta['project_client'][0];
        $client_image = $meta['project_gallery'][0];
        $url = $meta['project_url'][0];

        if($url !== '' && $url !== null){
            $the_url = '<form method="get" action="'.$url.'">
                            <button class="lsxp-button yellow">See website ></button>
                        </form>';
        }else{
            $the_url = '<button class="lsxp-button grey">Website not available</button>';
        }

        $client_image_post = get_post($client_image);
        $client_image = "<img src='" . $client_image_post->guid . "' />";

        $post_meta = get_post_meta($project->ID, 'project_product', false);

        $products = '';
        foreach ($post_meta as $key => $meta) {

            $products .= "
                <a href='".get_permalink(get_post($meta[0])->ID)."'>
                    ".get_post($meta[0])->post_title."
                </a>"
            ;


        }
        $terms = get_the_terms($project->ID, 'project_group');
        $industry = $terms[0]->name;

        $output = '
            <div class="lsxp-sidebar-section">
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Client</span>
                    <span class="lsxp-text">' . $client . '</span>
                    <span class="lsxp-img">' . $client_image . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Industry</span>
                    <span class="lsxp-text">Working on..</span>
                    <!--
                    <span class="lsxp-text-link" onclick="alert(\'Working on this and services\')">' . $industry . '</span>
                        list of all "industries/projects" linking to https://projects.invisionapp.com/d/main#/console/9237301/206977939/preview
                    -->
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Services</span>
                    <span class="lsxp-text">Working on..</span>
                    <!--
                        need to create "project-tag" into projects -> single project 
                        this new tag will be used to make relationship between projects
                    -->
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Products</span>
                    <span class="lsxp-text-link">' . $products . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-button">
                        '. $the_url .'
                    </span>
                </div>
            </div>
        ';

        return $output;

    }

}


$LSX_Project = new LSX_Project();