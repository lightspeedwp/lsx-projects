<?php
/**
 * LSX Projects Admin Class
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Projects_Admin {

	public function __construct() {
		$this->load_classes();

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_project_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_project_tag_setup' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'field_setup' ) );

		add_filter( 'cmb2_admin_init', array( $this, 'project_field_setup_product' ) );
		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	/**
	 * Loads the admin subclasses
	 */
	private function load_classes() {
		require_once LSX_PROJECTS_PATH . 'classes/admin/class-settings.php';
		$this->settings = \lsx\projects\classes\admin\Settings::get_instance();

		require_once LSX_PROJECTS_PATH . 'classes/admin/class-settings-theme.php';
		$this->settings_theme = \lsx\projects\classes\admin\Settings_Theme::get_instance();
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
	 * Add metabox with custom fields to the Project post type
	 */
	public function field_setup() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'title'        => __( 'General', 'lsx-projects' ),
				'object_types' => 'project',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Featured:', 'lsx-projects' ),
				'id'           => $prefix . 'featured',
				'type'         => 'checkbox',
				'value'        => 1,
				'default'      => 0,
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Add Alt Product metabox with custom fields to the Project post type
	 */
	public function project_field_setup_product() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'title'        => __( 'General', 'lsx-projects' ),
				'object_types' => 'project',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$tip_group = $cmb->add_field(
			array(
				'id'      => $prefix . '_alt_products',
				'type'    => 'group',
				'options' => array(
					'group_title'   => __( 'Alternative Products', 'lsx-projects' ),
					'add_button'    => __( 'Add Product', 'lsx-projects' ),
					'remove_button' => __( 'Remove Product', 'lsx-projects' ),
					'sortable'      => true,
				),
				'classes' => 'lsx-admin-row',
			)
		);

		$cmb->add_group_field(
			$tip_group,
			array(
				'name'         => esc_html__( 'Alt Product Name:', 'lsx-projects' ),
				'id'           => $prefix . 'alt_product_title',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_group_field(
			$tip_group,
			array(
				'name'         => esc_html__( 'Alt Product Link:', 'lsx-projects' ),
				'id'           => $prefix . 'alt_product_link',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

	}

	public function assets() {
		//wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-projects-admin', LSX_PROJECTS_URL . 'assets/js/lsx-projects-admin.min.js', array( 'jquery' ), LSX_PROJECTS_VER, true );
		wp_enqueue_style( 'lsx-projects-admin', LSX_PROJECTS_URL . 'assets/css/lsx-projects-admin.css', array(), LSX_PROJECTS_VER );
	}

	/**
	 * Change the "Insert into Post" button text when media modal is used for feature images
	 */
	public function change_attachment_field_button( $html ) {
		if ( isset( $_GET['feature_image_text_button'] ) ) {
			$html = str_replace( 'value="Insert into Post"', sprintf( 'value="%s"', esc_html__( 'Select featured image', 'lsx-projects' ) ), $html );
		}

		return $html;
	}

	public function change_title_text( $title ) {
		$screen = get_current_screen();

		if ( 'project' === $screen->post_type ) {
			$title = esc_attr__( 'Enter project title', 'lsx-projects' );
		}

		return $title;
	}
}

$lsx_projects_admin = new LSX_Projects_Admin();
