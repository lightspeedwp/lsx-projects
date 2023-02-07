<?php
namespace lsx\projects\classes;

/**
 * LSX Projects Frontend Class
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2017 LightSpeed
 */
class Frontend {

	/**
	 * Holds class instance
	 *
	 * @var      object \lsx_projects\classes\Frontend()
	 */
	protected static $instance = null;


	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		add_filter( 'excerpt_more_p', array( $this, 'change_excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_strip_tags', array( $this, 'change_excerpt_strip_tags' ) );
		add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ), 100 );

		add_filter( "term_links-project-tag", array( $this, 'term_link_tags' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object \lsx_projects\classes\Frontend()
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Enqueue the plugin scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'lsx-projects', LSX_PROJECTS_URL . 'assets/css/lsx-projects.css', array(), LSX_PROJECTS_VER );
		wp_style_add_data( 'lsx-projects', 'rtl', 'replace' );
	}

	/**
	 * Remove the "continue reading" when the single is disabled.
	 */
	public function change_excerpt_more( $excerpt_more ) {
		global $post;

		if ( 'project' === $post->post_type ) {
			$excerpt_more = '';
		}

		return $excerpt_more;
	}

	/**
	 * Change the word count when crop the content to excerpt (homepage widget).
	 */
	public function change_excerpt_length( $excerpt_word_count ) {
		global $post;

		if ( is_front_page() && 'project' === $post->post_type ) {
			$excerpt_word_count = 20;
		}

		if ( is_singular( 'project' ) && ( 'team' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$excerpt_word_count = 20;
		}

		return $excerpt_word_count;
	}

	/**
	 * Change the allowed tags crop the content to excerpt (homepage widget).
	 */
	public function change_excerpt_strip_tags( $allowed_tags ) {
		global $post;

		if ( is_front_page() && 'project' === $post->post_type ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		if ( is_singular( 'project' ) && ( 'team' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		return $allowed_tags;
	}

	/**
	 * Remove the "Archives:" from the post type archives.
	 *
	 * @param    $title
	 *
	 * @return    $title
	 */
	public function get_the_archive_title( $title ) {
		if ( is_post_type_archive( 'project' ) ) {
			$title = __( 'Portfolio', 'lsx-projects' );
		}

		if ( is_tax( array( 'project-group', 'project-type', 'project-tag' ) ) ) {
			$title = single_term_title( '', false );
		}

		return $title;
	}


	public function term_link_tags( $links ) {
		$new_links = array();
		foreach ( $links as $link ) {
			preg_match( "|<a.*[^>]*>([^<]*)</a>|i", $link, $matches );
			$class = sanitize_key( $matches[1] );
			$link = str_replace( 'rel="tag"', 'class="' . str_replace( " ", "-", $class ) . '" rel="tag"', $link );
			$new_links[] = $link;
		}
		return $new_links;
	}
}
