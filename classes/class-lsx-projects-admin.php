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
		add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'init', array( $this, 'create_settings_page' ), 100 );
		add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

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
	 * Add metabox with custom fields to the Project post type
	 */
	public function field_setup( $meta_boxes ) {
		$prefix = 'lsx_project_';

		$fields = array(
			array(
				'name' => esc_html__( 'Featured:', 'lsx-projects' ),
				'id'   => $prefix . 'featured',
				'type' => 'checkbox',
			),
			array(
				'name' => esc_html__( 'Client:', 'lsx-projects' ),
				'id'   => $prefix . 'client',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Client logo:', 'lsx-projects' ),
				'id'   => $prefix . 'client_logo',
				'type' => 'image',
				'desc' => esc_html__( 'Recommended image size: 320 x 50~60', 'lsx-projects' ),
			),
			array(
				'name' => esc_html__( 'URL for the finished project:', 'lsx-projects' ),
				'id'   => $prefix . 'url',
				'type' => 'text',
			),
		);

		// $fields[] = array(
		// 	'name' => esc_html__( 'Projects:', 'lsx-projects' ),
		// 	'id' => 'project_to_project',
		// 	'type' => 'post_select',
		// 	'use_ajax' => false,
		// 	'query' => array(
		// 		'post_type' => 'project',
		// 		'nopagin' => true,
		// 		'posts_per_page' => '50',
		// 		'orderby' => 'title',
		// 		'order' => 'ASC',
		// 	),
		// 	'repeatable' => true,
		// 	'allow_none' => true,
		// 	'cols' => 12,
		// );

		//if ( class_exists( 'LSX_Services' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Services related to this project:', 'lsx-projects' ),
				'id' => 'page_to_project',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'page',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		//}

		if ( class_exists( 'LSX_Testimonials' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Testimonials related to this project:', 'lsx-projects' ),
				'id' => 'testimonial_to_project',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'testimonial',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'LSX_Team' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Team members involved with this project:', 'lsx-projects' ),
				'id' => 'team_to_project',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'team',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'woocommerce' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Products used for this project:', 'lsx-projects' ),
				'id' => 'product_to_project',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'product',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Project Details', 'lsx-projects' ),
			'pages'  => 'project',
			'fields' => $fields,
		);

		return $meta_boxes;
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
	 * Returns the array of settings to the UIX Class
	 */
	public function create_settings_page() {
		if ( is_admin() ) {
			if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
				include_once LSX_PROJECTS_PATH . 'vendor/uix/uix.php';
				$pages = $this->settings_page_array();
				$uix = \lsx\ui\uix::get_instance( 'lsx' );
				$uix->register_pages( $pages );
			}

			if ( function_exists( 'tour_operator' ) ) {
				add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			} else {
				add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			}
		}
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function settings_page_array() {
		$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

		return array(
			'settings'  => array(
				'page_title'  => esc_html__( 'Theme Options', 'lsx-projects' ),
				'menu_title'  => esc_html__( 'Theme Options', 'lsx-projects' ),
				'capability'  => 'manage_options',
				'icon'        => 'dashicons-book-alt',
				'parent'      => 'themes.php',
				'save_button' => esc_html__( 'Save Changes', 'lsx-projects' ),
				'tabs'        => $tabs,
			),
		);
	}

	/**
	 * Register tabs
	 */
	public function register_tabs( $tabs ) {
		$default = true;

		if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
			$default = false;
		}

		if ( ! function_exists( 'tour_operator' ) ) {
			if ( ! array_key_exists( 'display', $tabs ) ) {
				$tabs['display'] = array(
					'page_title'        => '',
					'page_description'  => '',
					'menu_title'        => esc_html__( 'Display', 'lsx-projects' ),
					'template'          => LSX_PROJECTS_PATH . 'includes/settings/display.php',
					'default'           => $default,
				);

				$default = false;
			}
		}

		return $tabs;
	}

	/**
	 * Outputs the display tabs settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function display_settings( $tab = 'general' ) {
		if ( 'projects' === $tab ) {
			$this->disable_single_post_field();
			$this->placeholder_field();
			$this->contact_modal_fields();
		}
	}

	/**
	 * Outputs the Display flags checkbox
	 */
	public function disable_single_post_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="projects_disable_single"><?php esc_html_e( 'Disable Single Posts', 'lsx-projects' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if projects_disable_single}} checked="checked" {{/if}} name="projects_disable_single" />
				<small><?php esc_html_e( 'Disable Single Posts.', 'lsx-projects' ); ?></small>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the flag position field
	 */
	public function placeholder_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Placeholder', 'lsx-projects' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if projects_placeholder_id}} value="{{projects_placeholder_id}}" {{/if}} name="projects_placeholder_id" />
				<input class="input_image" type="hidden" {{#if projects_placeholder}} value="{{projects_placeholder}}" {{/if}} name="projects_placeholder" />
				<div class="thumbnail-preview">
					{{#if projects_placeholder}}<img src="{{projects_placeholder}}" width="150" />{{/if}}
				</div>
				<a {{#if projects_placeholder}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="projects_placeholder"><?php esc_html_e( 'Choose Image', 'lsx-projects' ); ?></a>
				<a {{#unless projects_placeholder}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="projects_placeholder"><?php esc_html_e( 'Delete', 'lsx-projects' ); ?></a>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the contact modal fields.
	 */
	public function contact_modal_fields() {
		?>
		<tr class="form-field">
			<th scope="row" colspan="2">
				<h2><?php esc_html_e( 'Contact modal', 'lsx-projects' ); ?></h2>
			</th>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="projects_modal_enable"><?php esc_html_e( 'Enable contact modal', 'lsx-projects' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if projects_modal_enable}} checked="checked" {{/if}} name="projects_modal_enable" />
				<small><?php esc_html_e( 'Displays contact modal on project single.', 'lsx-projects' ); ?></small>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="projects_modal_cta_label"><?php esc_html_e( 'Button label', 'lsx-projects' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if projects_modal_cta_label}} value="{{projects_modal_cta_label}}" {{/if}} name="projects_modal_cta_label" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="projects_modal_form_id"><?php esc_html_e( 'Caldera Form ID', 'lsx-projects' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if projects_modal_form_id}} value="{{projects_modal_form_id}}" {{/if}} name="projects_modal_form_id" />
			</td>
		</tr>
		<?php
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
