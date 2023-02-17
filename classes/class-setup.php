<?php
namespace lsx\projects\classes;

/**
 * LSX Projects Setup Class
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class Setup {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx_projects\classes\Setup()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_project_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_project_tag_setup' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object \lsx_projects\classes\Setup()
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register the Project and Product Tag post type
	 */
	public function post_type_setup() {
		$labels = array(
			'name'               => esc_html_x( 'Projects', 'post type general name', 'lsx-projects' ),
			'singular_name'      => esc_html_x( 'Project', 'post type singular name', 'lsx-projects' ),
			'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-projects' ),
			'add_new_item'       => esc_html__( 'Add New Project', 'lsx-projects' ),
			'edit_item'          => esc_html__( 'Edit Project', 'lsx-projects' ),
			'new_item'           => esc_html__( 'New Project', 'lsx-projects' ),
			'all_items'          => esc_html__( 'All Projects', 'lsx-projects' ),
			'view_item'          => esc_html__( 'View Project', 'lsx-projects' ),
			'search_items'       => esc_html__( 'Search Projects', 'lsx-projects' ),
			'not_found'          => esc_html__( 'No projects found', 'lsx-projects' ),
			'not_found_in_trash' => esc_html__( 'No projects found in Trash', 'lsx-projects' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html_x( 'Projects', 'admin menu', 'lsx-projects' ),
		);

		$single_template = file_get_contents( LSX_PROJECTS_PATH . 'templates/single-project.html' );

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-portfolio',
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'portfolio',
			),
			'capability_type'    => 'post',
			'has_archive'        => 'portfolio',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'custom-fields',
			),
			'show_in_rest'       => true,
			/*'template'           => array(
				array( 'core/pattern', array(
					'slug' => 'lsx-projects/related-projects',
				) ),
			),*/
		);

		register_post_type( 'project', $args );
	}

	/**
	 * Register the Group taxonomy
	 */
	public function taxonomy_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Project Groups', 'taxonomy general name', 'lsx-projects' ),
			'singular_name'     => esc_html_x( 'Group', 'taxonomy singular name', 'lsx-projects' ),
			'search_items'      => esc_html__( 'Search Groups', 'lsx-projects' ),
			'all_items'         => esc_html__( 'All Groups', 'lsx-projects' ),
			'parent_item'       => esc_html__( 'Parent Group', 'lsx-projects' ),
			'parent_item_colon' => esc_html__( 'Parent Group:', 'lsx-projects' ),
			'edit_item'         => esc_html__( 'Edit Group', 'lsx-projects' ),
			'update_item'       => esc_html__( 'Update Group', 'lsx-projects' ),
			'add_new_item'      => esc_html__( 'Add New Group', 'lsx-projects' ),
			'new_item_name'     => esc_html__( 'New Group Name', 'lsx-projects' ),
			'menu_name'         => esc_html__( 'Groups', 'lsx-projects' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'portfolio-group',
			),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'project-group', array( 'project' ), $args );
	}

	/**
	 * Register the Type taxonomy
	 */
	public function taxonomy_project_type_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Project Types', 'taxonomy general name', 'lsx-projects' ),
			'singular_name'     => esc_html_x( 'Type', 'taxonomy singular name', 'lsx-projects' ),
			'search_items'      => esc_html__( 'Search Types', 'lsx-projects' ),
			'all_items'         => esc_html__( 'All Types', 'lsx-projects' ),
			'parent_item'       => esc_html__( 'Parent Type', 'lsx-projects' ),
			'parent_item_colon' => esc_html__( 'Parent Type:', 'lsx-projects' ),
			'edit_item'         => esc_html__( 'Edit Type', 'lsx-projects' ),
			'update_item'       => esc_html__( 'Update Type', 'lsx-projects' ),
			'add_new_item'      => esc_html__( 'Add New Type', 'lsx-projects' ),
			'new_item_name'     => esc_html__( 'New Type Name', 'lsx-projects' ),
			'menu_name'         => esc_html__( 'Types', 'lsx-projects' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'project-type',
			),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'project-type', array( 'project' ), $args );
	}

	/**
	 * Register the Tag taxonomy
	 */
	public function taxonomy_project_tag_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Project Tags', 'taxonomy general name', 'lsx-projects' ),
			'singular_name'     => esc_html_x( 'Tag', 'taxonomy singular name', 'lsx-projects' ),
			'search_items'      => esc_html__( 'Search Tags', 'lsx-projects' ),
			'all_items'         => esc_html__( 'All Tags', 'lsx-projects' ),
			'parent_item'       => esc_html__( 'Parent Tag', 'lsx-projects' ),
			'parent_item_colon' => esc_html__( 'Parent Tag:', 'lsx-projects' ),
			'edit_item'         => esc_html__( 'Edit Tag', 'lsx-projects' ),
			'update_item'       => esc_html__( 'Update Tag', 'lsx-projects' ),
			'add_new_item'      => esc_html__( 'Add New Tag', 'lsx-projects' ),
			'new_item_name'     => esc_html__( 'New Tag Name', 'lsx-projects' ),
			'menu_name'         => esc_html__( 'Tags', 'lsx-projects' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'project-tag',
			),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'project-tag', array( 'project' ), $args );
	}

	/**
	 * Add our action to init to set up our vars first.
	 */
	function load_plugin_textdomain() {
		load_plugin_textdomain( 'lsx-projects', false, basename( LSX_PROJECTS_PATH ) . '/languages' );
	}
}
