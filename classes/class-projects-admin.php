<?php

class LSX_Project_Admin {

	public function __construct()
	{
		if ( ! class_exists('CMB_Meta_Box'))
			require_once( plugin_dir_path( __FILE__ ) . '/Custom-Meta-Boxes/custom-meta-boxes.php' );

	    add_action( 'init', array( $this, 'post_type_setup' ) );
	    add_action( 'init', array( $this, 'taxonomy_setup' ) );
	    add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
	    // add_action( 'admin_init', array( $this, 'shortcode_ui' ) );
	    // add_action( 'admin_enqueue_scripts', array( $this, 'shortcode_ui_style' ) );
	    // add_action( 'admin_enqueue_scripts', array( $this, 'add_media_js' ) );
	}

	/**
	 * Register the Project post type
	 */
	public function post_type_setup() 
	{
		$labels = array(
		    'name'               => _x( 'Projects', 'post type general name', 'bs-project' ),
		    'singular_name'      => _x( 'Project', 'post type singular name', 'bs-project' ),
		    'add_new'            => _x( 'Add New', 'post type general name', 'bs-project' ),
		    'add_new_item'       => __( 'Add New Project', 'bs-project' ),
		    'edit_item'          => __( 'Edit Project', 'bs-project' ),
		    'new_item'           => __( 'New Project', 'bs-project' ),
		    'all_items'          => __( 'All Projects', 'bs-project' ),
		    'view_item'          => __( 'View Project', 'bs-project' ),
		    'search_items'       => __( 'Search Projects', 'bs-project' ),
		    'not_found'          => __( 'No projects found', 'bs-project' ),
		    'not_found_in_trash' => __( 'No projects found in Trash', 'bs-project' ),
		    'parent_item_colon'  => '',
		    'menu_name'          => _x( 'Projects', 'admin menu', 'bs-project' )
		);

		$args = array(
		    'labels'             => $labels,
		    'public'             => true,
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => true,
		    'menu_icon'			=> 'dashicons-portfolio',
		    'query_var'          => true,
		    'rewrite'            => array( 'slug' => 'portfolio' ),
		    'capability_type'    => 'post',
		    'has_archive'        => 'portfolio',
		    'hierarchical'       => false,
		    'menu_position'      => null,
		    'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' )
		);

		register_post_type( 'project', $args );
	}

	/**
	 * Register the Role taxonomy
	 */
	public function taxonomy_setup()
	{
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Project Groups', 'taxonomy general name', 'bs-project' ),
			'singular_name'     => _x( 'Project Group', 'taxonomy singular name', 'bs-project' ),
			'search_items'      => __( 'Search Project Groups', 'bs-project' ),
			'all_items'         => __( 'All Project Groups', 'bs-project' ),
			'parent_item'       => __( 'Parent Project Group', 'bs-project' ),
			'parent_item_colon' => __( 'Parent Project Group:', 'bs-project' ),
			'edit_item'         => __( 'Edit Project Group', 'bs-project' ),
			'update_item'       => __( 'Update Project Group', 'bs-project' ),
			'add_new_item'      => __( 'Add New Project Group', 'bs-project' ),
			'new_item_name'     => __( 'New Project Group Name', 'bs-project' ),
			'menu_name'         => __( 'Project Groups', 'bs-project' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,			
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'project-group' ),
		);

		register_taxonomy( 'project_group', array( 'project' ), $args );

	}
	
	/**
	 * Add metabox with custom fields to the Project post type
	 */
	public function field_setup( $meta_boxes ) 
	{
	    $prefix = 'project_'; // Prefix for all fields
	    
	    $fields = array(
	    	array(
	            'name' => __( 'Client:', 'bs-project' ),              
	            'id' => $prefix . 'client',
	            'type' => 'text'
	        ),
	        array(
	            'name' => __( 'URL:', 'bs-project' ),	                
	            'id' => $prefix . 'url',
	            'type' => 'text'
	        ),
	        array(
	            'name' => __( 'Gallery:', 'bs-project' ),
	            'id' => $prefix . 'gallery',
	            'type' => 'image',
	            'repeatable' => true
	        ),
            array(
                'name' => __( 'Testimonials:', 'bs-project' ),
                'id' => $prefix . 'testimonials',
                'type' => 'post_select',
                'query' => array(
                    'post_type' => 'testimonial'
                ),
                'multiple' => true
            ),
            array(
                'name' => __( 'Documentation:', 'bs-project' ),
                'id' => $prefix . 'documentation',
                'type' => 'post_select',
                'query' => array(
                    'post_type' => 'documentation'
                ),
                'multiple' => true
            ),
            array(
                'name' => __( 'Products:', 'bs-project' ),
                'id' => $prefix . 'product',
                'type' => 'post_select',
                'query' => array(
                    'post_type' => 'product'
                ),
                'multiple' => true
            ),
            array(
                'name' => __( 'WooCommerce:', 'bs-project' ),
                'id' => $prefix . 'woocommerce',
                'type' => 'post_select',
                'query' => array(
                    'post_type' => 'woocommerce'
                ),
                'multiple' => true
            ),
	        array( 
	        	'name' => __( 'Featured:', 'bs-project' ),
				'id' => $prefix . 'featured',  				
				'type'    => 'radio', 
				'options' => array( 
				    '1' => 'Yes', 
				    '0' => 'No'
					),
				'default' => '0'
				),        
	    );
	    
	    $meta_boxes[] = array(
			'title' => __( 'Project Details', 'bs-project' ),
			'pages' => 'project',
			'fields' => $fields
		);

	    return $meta_boxes;
	}

	/**
	 * Add custom shortcode button to the editor
	 */
	function shortcode_ui() 
	{
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	/**
	 * Add a seperation before the custom editor button
	 */
	function filter_mce_button( $buttons ) 
	{
		// add a separation before our button, here our button's id is "project_button"
		array_push( $buttons, '|', 'project_button' );
		return $buttons;
	}
	
	/**
	 * Include the custom Tiny MCE project shortcode js plugin file
	 */
	function filter_mce_plugin( $plugins ) 
	{
		// this plugin file will work the magic of our button
		$plugins['project'] = dirname( plugin_dir_url( __FILE__ ) ) . '/js/mce_project_plugin.js';
		return $plugins;
	}

	/**
	 * Enqueue styling for the custom editor button
	 */
	function shortcode_ui_style() 
	{
		wp_enqueue_style( 'tinymce-project', dirname( plugin_dir_url( __FILE__ ) ) . '/css/tinymce.css'  );
	}

	function add_media_js( $plugins ) 
	{
		// this plugin file will work the magic of our button
		wp_enqueue_media();
		wp_enqueue_script( 'add-media', dirname( plugin_dir_url( __FILE__ ) ) . '/js/add-media.js', array( 'jQuery' ) );		
	}
}

$LSX_Project_Admin = new LSX_Project_Admin();