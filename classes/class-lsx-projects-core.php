<?php

/**
 * This class loads the other classes and function files
 *
 * @package lsx-projects
 */
class LSX_Projects_Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_projects\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \MAG_CMB2_Field_Post_Search_Ajax()
	 */
	public $cmb2_post_search_ajax = false;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_vendors();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_projects\classes\Core()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_vendors() {
		// Configure custom fields.
		if ( ! class_exists( 'CMB2' ) ) {
			require_once LSX_PROJECTS_PATH . 'vendor/CMB2/init.php';
		}
	}

	/**
	 * Returns the post types currently active
	 *
	 * @return void
	 */
	public function get_post_types() {
		$post_types = apply_filters( 'lsx_projects_post_types', isset( $this->post_types ) );
		foreach ( $post_types as $index => $post_type ) {
			$is_disabled = \cmb2_get_option( 'lsx_projects_options', $post_type . '_disabled', false );
			if ( true === $is_disabled || 1 === $is_disabled || 'on' === $is_disabled ) {
				unset( $post_types[ $index ] );
			}
		}
		return $post_types;
	}
}

LSX_Projects_Core::get_instance();
