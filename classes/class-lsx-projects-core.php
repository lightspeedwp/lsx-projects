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
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
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
	 * Loads the classes
	 */
	private function load_classes() {
		require_once LSX_PROJECTS_PATH . '/classes/class-block-patterns.php';
		\lsx\projects\classes\Block_Patterns::get_instance();
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
