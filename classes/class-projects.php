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
                    $image = get_the_post_thumbnail_url($post->ID, array('880px','600px'));
                }
            }

            if($count > 0){
            ?>
                <header class="archive-header"
                    style="
                        /*max-height: 600px;*/
                        background-position: center center;
                        background-image: url('<?=$image?>') !important;
                        top: -130px; bottom: -130px;"
                        data-banners="<?=$image?>";
                >
                    <div id="lsx-banner">
                        <div class="page-banner-wrap">
                            <div class="page-banner rotating"
                                 style="/*max-height: initial;*/"
                            >
                                <div class="page-banner-image" style="background: transparent !important;"></div>
                                <div class="container" style="padding-top: 0px;">
                                    <header class="page-header">
                                        <p>Featured project:</p>
                                        <h1 class="page-title"><?=$title?></h1>
                                        <p><?=$subtitle?></p>
                                    </header>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            <?php
            }else{
                ?>
                <header class="archive-header">
                    <h1 class="archive-title">LSX Portfolio</h1>
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
                $group = get_the_terms($project->ID, 'project_group');
                $group_id = $group[0]->term_taxonomy_id;

                $args = [
                    'taxonomy' => 'project_group',
                    'term_taxonomy_id' => $group_id,
                    'orderby' => 'name',
                    'order' => 'asc'
                ];

                //working on..
                $data = get_terms($args);

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
                        $image = get_the_post_thumbnail($project->ID, array('480px', '320px'),
                            'class=img-responsive project-image');

                        if(strpos($image, '~text?')){
                            $image = "<img src='http://placehold.it/480x320/' alt='placeholder' class='img-responsive project-image' />";
                        }
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
                        $industry_link = str_replace(['&','-','_',' ','amp;'], [''], trim($subtitle[0]->name));
                        $output .= "
                    <article data-column='3' class='filter-item column-3 $industry_link'>
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
                              <li class='allBtn'><a href=\"#\" id='all' data-filter=\"*\" class=\"selected\">All</a></li>";
                foreach ($data as $return) {

                    $industry_link = str_replace(['&','-','_',' ','amp;'], [''], trim($return->name));

                    $output .= "<li><a href=\"#\" id='{$industry_link}' data-filter=\"." . $industry_link . "\" class=\"\">$return->name</a></li>";
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
        $client_image = ($client_image_post->post_mime_type !== '' ? "<img src='" . $client_image_post->guid . "' />" : '');

        $post_meta = get_post_meta($project->ID, 'project_product', false);
        $products = '';
        $count = count($post_meta);
        $new_count = 0;
        foreach ($post_meta as $key => $meta) {
            $products_ids[] = $meta[0];

            $products .= "
                <a href='".get_permalink(get_post($meta[0])->ID)."'>
                    ".get_post($meta[0])->post_title."
                </a>"
            ;

            if($new_count !== $count){
                $products .= ", ";
            }
        }

        $terms = get_the_terms($project->ID, 'project_group');
        $industry = $terms[0]->name;
        $industry_link = str_replace(['&','-','_',' ','amp;'], [''], trim($terms[0]->name));

        $services = wp_get_object_terms( $products_ids, 'product_tag' );
        $services_name = '';
        $count = count($services);
        $new_count = 0;
        foreach ($services as $service){
            $new_count++;
            $services_name .= "
                <a href='/product-tag/$service->slug'>$service->name</a> 
            ";

            if($new_count !== $count){
                $services_name .= ", ";
            }
        }


        $output = '
            <div class="lsxp-sidebar-section">
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Client</span>
                    <span class="lsxp-text">' . $client . '</span>
                    <span class="lsxp-img">' . $client_image . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Industry</span>
                    <span class="lsxp-text-link" onclick="location.href=\'portfolio/#'.$industry_link.'\'">' . $industry . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Services</span>
                    <span class="lsxp-text">' . ($services_name !== '' ? $services_name : 'No products related.') . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Products</span>
                    <span class="lsxp-text">' . ($products !== '' ? $products : 'No products related.') . '</span>
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

    public function single_tag(){

        $args = array(
            'post_type' => 'project',
            'orderby' => 'name',
            'order' => 'asc'
        );

        $projects = get_posts($args);

        foreach ($projects as $project){
            $check = get_post_meta($project->ID,'project_product');
            if(!empty($check)){
                $projects_list[] = $project;
            }
        }

        extract(shortcode_atts(array(
            'columns' => 3,
            'orderby' => 'name',
            'order' => 'ASC',
            'limit' => '-1',
            'group' => '',
            'include' => '',
            'size' => 320,
        ), $atts));

        $count = 0;
        if ($columns >= 1 && $columns <= 4) {
            $output .= "<div class='filter-items-wrapper lsx-portfolio-wrapper'>
                                <div id='portfolio-infinite-scroll-wrapper' class='filter-items-container lsx-portfolio masonry'>";
        }

        foreach ($projects_list as $project) {
            // Vars
            $count++;
            if (has_post_thumbnail($project->ID)) {
                $image = get_the_post_thumbnail($project->ID, array('480px', '320px'),
                    'class=img-responsive project-image');

                if(strpos($image, '~text?')){
                    $image = "<img src='http://placehold.it/480x320/' alt='placeholder' class='img-responsive project-image' />";
                }
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
                $industry_link = str_replace(['&','-','_',' ','amp;'], [''], trim($subtitle[0]->name));
                $output .= "
                    <article data-column='3' class='filter-item column-3 $industry_link'>
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


$LSX_Project = new LSX_Project();