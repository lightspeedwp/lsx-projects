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
		add_filter( 'cmb2_admin_init', array( $this, 'projects_services_metaboxes' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'projects_testimonials_metaboxes' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'projects_team_metaboxes' ) );
		add_filter( 'cmb2_admin_init', array( $this, 'projects_woocommerce_metaboxes' ) );
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
			),
			'show_in_rest'       => true,
			'supports'           => array( 'editor', 'title', 'excerpt', 'thumbnail' ),
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

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Client:', 'lsx-projects' ),
				'id'           => $prefix . 'client',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Client logo:', 'lsx-projects' ),
				'id'           => $prefix . 'client_logo',
				'type'         => 'file',
				'desc'         => esc_html__( 'Recommended image size: 320 x 50~60', 'lsx-projects' ),
				'options'      => array(
					'url' => false, // Hide the text input for the url.
				),
				'text'         => array(
					'add_upload_file_text' => 'Choose Image',
				),
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'URL for the finished project:', 'lsx-projects' ),
				'id'           => $prefix . 'url',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Project Services Metaboxes.
	 */
	public function projects_services_metaboxes() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'object_types' => 'projects',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Services related to this project:', 'lsx-projects' ),
				'id'           => 'page_to_project',
				'type'         => 'post_search_ajax',
				'show_in_rest' => true,
				'limit'        => 15,
				'sortable'     => true,
				'query_args'   => array(
					'post_type'      => array( 'project' ),
					'post_status'    => array( 'publish' ),
					'nopagin'        => true,
					'posts_per_page' => '50',
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
			)
		);
	}

	/**
	 * Project Testimonials Metaboxes.
	 */
	public function projects_testimonials_metaboxes() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'object_types' => 'projects',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		if ( class_exists( 'LSX_Testimonials' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Testimonials related to this project:', 'lsx-projects' ),
					'id'           => 'testimonial_to_project',
					'type'         => 'post_search_ajax',
					'show_in_rest' => true,
					'limit'        => 15,
					'sortable'     => true,
					'query_args'   => array(
						'post_type'      => array( 'testimonial' ),
						'post_status'    => array( 'publish' ),
						'nopagin'        => true,
						'posts_per_page' => '50',
						'orderby'        => 'title',
						'order'          => 'ASC',
					),
				)
			);
		}
	}

	/**
	 * Project Team Metaboxes.
	 */
	public function projects_team_metaboxes() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'object_types' => 'project',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		if ( class_exists( 'LSX_Team' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Team members involved with this project:', 'lsx-projects' ),
					'id'           => 'team_to_project',
					'type'         => 'post_search_ajax',
					'show_in_rest' => true,
					'limit'        => 15,
					'sortable'     => true,
					'query_args'   => array(
						'post_type'      => array( 'team' ),
						'post_status'    => array( 'publish' ),
						'nopagin'        => true,
						'posts_per_page' => '50',
						'orderby'        => 'title',
						'order'          => 'ASC',
					),
				)
			);
		}
	}

	/**
	 * Project Woocommerce Metaboxes.
	 */
	public function projects_woocommerce_metaboxes() {
		$prefix = 'lsx_project_';

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_project',
				'object_types' => 'projects',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		if ( class_exists( 'woocommerce' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Products used for this project:', 'lsx-projects' ),
					'id'           => 'product_to_project',
					'type'         => 'post_search_ajax',
					'show_in_rest' => true,
					'limit'        => 15,
					'sortable'     => true,
					'query_args'   => array(
						'post_type'      => array( 'product' ),
						'post_status'    => array( 'publish' ),
						'nopagin'        => true,
						'posts_per_page' => '50',
						'orderby'        => 'title',
						'order'          => 'ASC',
					),
				)
			);
		}
		if ( ! class_exists( 'woocommerce' ) ) {
			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Alt Product Name:', 'lsx-projects' ),
					'id'           => $prefix . 'alt_product_title',
					'type'         => 'text',
					'show_in_rest' => true,
				)
			);

			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Alt Product Link:', 'lsx-projects' ),
					'id'           => $prefix . 'alt_product_link',
					'type'         => 'text',
					'show_in_rest' => true,
				)
			);

			$cmb->add_field(
				array(
					'name'         => esc_html__( 'Alt Product Image:', 'lsx-projects' ),
					'id'           => $prefix . 'alt_product_image',
					'type'         => 'file',
					'desc'         => esc_html__( 'Recommended image size: 320 x 50~60', 'lsx-projects' ),
					'options'      => array(
						'url' => false, // Hide the text input for the url.
					),
					'text'         => array(
						'add_upload_file_text' => 'Choose Image',
					),
					'show_in_rest' => true,
				)
			);
		}
	}

	/**
	 * Sets up the "post relations".
	 */
	public function post_relations( $post_id, $field, $value ) {
		$connections = array(
			// 'project_to_project',

			'page_to_project',
			'project_to_page',

			'project_to_service',
			'service_to_project',

			'project_to_testimonial',
			'testimonial_to_project',

			'project_to_team',
			'team_to_project',
		);

		if ( in_array( $field['id'], $connections ) ) {
			$this->save_related_post( $connections, $post_id, $field, $value );
		}
	}

	/**
	 * Save the reverse post relation.
	 */
	public function save_related_post( $connections, $post_id, $field, $value ) {
		$ids = explode( '_to_', $field['id'] );
		$relation = $ids[1] . '_to_' . $ids[0];

		if ( in_array( $relation, $connections ) ) {
			$previous_values = get_post_meta( $post_id, $field['id'], false );

			if ( ! empty( $previous_values ) ) {
				foreach ( $previous_values as $v ) {
					delete_post_meta( $v, $relation, $post_id );
				}
			}

			if ( is_array( $value ) ) {
				foreach ( $value as $v ) {
					if ( ! empty( $v ) ) {
						add_post_meta( $v, $relation, $post_id );
					}
				}
			}
		}
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
