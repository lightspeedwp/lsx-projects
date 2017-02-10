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

        $client_image_post = get_post($client_image);
        $client_image = "<img src='" . $client_image_post->guid . "' />";

        $post_meta = get_post_meta($project->ID, 'project_product', false);
        $count = count($post_meta);
        $products = '';
        foreach ($post_meta as $key => $meta) {
            if ($count - 1 == $key) {
                $products .= get_post($meta[0])->post_title;
            } else {
                $products .= get_post($meta[0])->post_title . ', ';
            }
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
                    <span class="lsxp-text-link">' . $industry . '</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Services</span>
                    <span class="lsxp-text-link">Nothing till now</span>
                </div>
        
                <div class="lsxp-sidebar">
                    <span class="lsxp-title">Products</span>
                    <span class="lsxp-text-link">' . $products . '</span>
                </div>
            </div>
        ';

        return $output;

    }

}


$LSX_Project = new LSX_Project();