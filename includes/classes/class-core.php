<?php
namespace LSX\Projects\Classes;

/**
 * The main file loading the rest of the files
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2023 LightSpeed
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @var      object \LSX\Projects\Classes\Core()
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
	 * @return    object \LSX\Projects\Classes\Core()
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
		require_once LSX_PROJECTS_PATH . '/includes/classes/class-block-patterns.php';
		\LSX\Projects\Classes\Block_Patterns::get_instance();

		require_once LSX_PROJECTS_PATH . '/includes/classes/class-setup.php';
		\LSX\Projects\Classes\Setup::get_instance();

		require_once LSX_PROJECTS_PATH . '/includes/classes/class-frontend.php';
		\LSX\Projects\Classes\Frontend::get_instance();

		// Post reorder.
		require_once LSX_PROJECTS_PATH . '/includes/classes/class-lsx-projects-scpo-engine.php';
		$lsx_projects_scporder = new \LSX_Projects_SCPO_Engine();
	}
}
