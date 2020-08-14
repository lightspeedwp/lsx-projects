<?php
/**
 * Contains the settings class for LSX
 *
 * @package lsx-projects
 */

namespace lsx\projects\classes\admin;

class Settings {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_projects\classes\admin\Settings()
	 */
	protected static $instance = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var string
	 */
	protected $screen_id = 'lsx_projects_settings';

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'register_settings_page' ) );
		add_action( 'lsx_projects_settings_page', array( $this, 'general_settings' ), 1, 1 );
		add_action( 'lsx_projects_settings_page', array( $this, 'contact_modal_settings' ), 1, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object Settings()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in and register a submenu options page for the Page post-type menu.
	 */
	public function register_settings_page() {
		$cmb = new_cmb2_box(
			array(
				'id'           => $this->screen_id,
				'title'        => esc_html__( 'Settings', 'lsx-projects' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx_projects_options', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=project', // Make options page a submenu item of the themes menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);
		do_action( 'lsx_projects_settings_page', $cmb );
	}

	/**
	 * Registers the general settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function general_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'      => 'settings_general_title',
				'type'    => 'title',
				'name'    => __( 'General', 'lsx-projects' ),
				'default' => __( 'General', 'lsx-projects' ),
			)
		);
		$cmb->add_field(
			array(
				'name'        => __( 'Disable Single Posts', 'lsx-projects' ),
				'id'          => 'projects_disable_single',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Disable Single Posts.', 'lsx-projects' ),
			)
		);

		$cmb->add_field(
			array(
				'name'    => 'Placeholder',
				'desc'    => __( 'Choose Image.', 'lsx-projects' ),
				'id'      => 'projects_placeholder_id',
				'type'    => 'file',
				'options' => array(
					'url' => false, // Hide the text input for the url.
				),
				'text'    => array(
					'add_upload_file_text' => 'Choose Image',
				),
			)
		);

		$cmb->add_field(
			array(
				'id'   => 'settings_general_closing',
				'type' => 'tab_closing',
			)
		);
	}

	/**
	 * Registers the Contact modal settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function contact_modal_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'   => 'settings_contact_modal_title',
				'type' => 'title',
				'name' => __( 'Contact modal', 'lsx-projects' ),
			)
		);
		$cmb->add_field(
			array(
				'name'        => __( 'Enable contact modal', 'lsx-projects' ),
				'id'          => 'projects_modal_enable',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Displays contact modal on project single.', 'lsx-projects' ),
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Button label', 'lsx-projects' ),
				'id'   => 'projects_modal_cta_label',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Caldera Form ID', 'lsx-projects' ),
				'id'   => 'projects_modal_form_id',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'id'   => 'settings_contact_modal_closing',
				'type' => 'tab_closing',
			)
		);
	}

}
