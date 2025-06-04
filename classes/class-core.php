<?php
namespace lsx\projects\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-projects
 */
class Core {

	/**
	 * Holds class instance
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
	 * @return    object \lsx_projects\classes\Core()
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

		require_once LSX_PROJECTS_PATH . '/classes/class-setup.php';
		\lsx\projects\classes\Setup::get_instance();

		require_once LSX_PROJECTS_PATH . '/classes/class-frontend.php';
		\lsx\projects\classes\Frontend::get_instance();
	}
}
Core::get_instance();