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
	 * Loads the classes
	 */
	private function load_classes() {
		require_once LSX_PROJECTS_PATH . '/classes/class-block-patterns.php';
		\lsx\projects\classes\Block_Patterns::get_instance();
	}
}

LSX_Projects_Core::get_instance();
