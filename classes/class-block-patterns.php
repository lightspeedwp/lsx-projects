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
class Block_Patterns {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Holds the slug of the projects pattern category.
	 *
	 * @var string
	 */
	private $pattern_category = 'lsx-projects';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function __construct() {
		//Register our pattern category
		add_action( 'init', array( $this, 'register_block_category' ) );

		// Register our block patterns
		add_action( 'init', array( $this, 'register_block_patterns' ), 10 );

		// Register our the content filters.
		add_filter( 'query_loop_block_query_vars', array( $this, 'replace_related_vars' ), 10, 3 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\projects\classes\Block_Patterns();    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Registers the projects pattern category
	 *
	 * @return void
	 */
	public function register_block_category() {
		register_block_pattern_category(
			$this->pattern_category,
			array( 'label' => __( 'LSX Projects', 'lsx-projects' ) )
		);
	}  

	/**
	 * Registers our block patterns with the 
	 *
	 * @return void
	 */
	public function register_block_patterns() {
		$patterns = array(
			//'lsx-projects/featured-basic' => $this->featured_basic(),
			//'lsx-projects/mobile-gallery-basic' => $this->mobile_gallery_basic(),
			'lsx-projects/related-projects' => $this->related_portfolio(),
		);

		foreach ( $patterns as $key => $function ) {
			register_block_pattern( $key, $function );
		}
	}

	/**
	 * Portfolio - Featured Pattern - Basic Style
	 *
	 * @return void
	 */
	public function featured_basic() {
		return array(
			'title'       => __( 'Featured Info', 'lsx-projects' ),
			'description' => _x( '', 'Block pattern description', 'lsx-projects' ),
			'categories'  => array( $this->pattern_category ),
			'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"var:preset|spacing|x-small","right":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--x-small);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--x-small)">
			
			<!-- wp:post-title {"level":1,"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|x-large"}}},"className":"wp-block-heading","fontSize":"max-72"} /-->
			
			<!-- wp:columns {"verticalAlignment":"top","align":"wide"} -->
			<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"66.66%","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"var:preset|spacing|small","left":"0"}}}} -->
			<div class="wp-block-column is-vertically-aligned-top" style="padding-top:0;padding-right:0;padding-bottom:var(--wp--preset--spacing--small);padding-left:0;flex-basis:66.66%"><!-- wp:image {"id":40967,"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2023/01/case-study-savvy-hero-1024x563.png" alt="" class="wp-image-40967"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column -->
			
			<!-- wp:column {"verticalAlignment":"top","width":"33.33%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:33.33%"><!-- wp:image {"id":40970,"width":280,"height":71,"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large is-resized"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2023/01/case-study-logo-savvy.svg" alt="" class="wp-image-40970" width="280" height="71"/></figure>
			<!-- /wp:image -->
			
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--x-small)"><!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><strong>Type of site:</strong> Redesign + Custom Plugin Development</p>
			<!-- /wp:paragraph -->
			
			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><strong>Services</strong></p>
			<!-- /wp:paragraph -->
			
			<!-- wp:list -->
			<ul><!-- wp:list-item {"className":"has-small-font-size"} -->
			<li class="has-small-font-size">Discovery</li>
			<!-- /wp:list-item -->
			
			<!-- wp:list-item {"className":"has-small-font-size"} -->
			<li class="has-small-font-size">Development<!-- wp:list -->
			<ul><!-- wp:list-item -->
			<li>Custom Plugin Development</li>
			<!-- /wp:list-item -->
			
			<!-- wp:list-item -->
			<li>API Development</li>
			<!-- /wp:list-item --></ul>
			<!-- /wp:list --></li>
			<!-- /wp:list-item -->
			
			<!-- wp:list-item {"className":"has-small-font-size"} -->
			<li class="has-small-font-size">Support &amp; Maintenance</li>
			<!-- /wp:list-item -->
			
			<!-- wp:list-item {"className":"has-small-font-size"} -->
			<li class="has-small-font-size">Performance Optimisation</li>
			<!-- /wp:list-item -->
			
			<!-- wp:list-item {"className":"has-small-font-size"} -->
			<li class="has-small-font-size">Security</li>
			<!-- /wp:list-item --></ul>
			<!-- /wp:list -->
			
			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"width":100,"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","right":"var:preset|spacing|small","bottom":"var:preset|spacing|small","left":"var:preset|spacing|small"}}},"className":"is-style-outline","fontSize":"medium"} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size is-style-outline has-medium-font-size"><a class="wp-block-button__link wp-element-button" style="padding-top:var(--wp--preset--spacing--small);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small);padding-left:var(--wp--preset--spacing--small)">See Website</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div>
			<!-- /wp:group --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns --></div>
			<!-- /wp:group -->',
		);
	}

	/**
	 * Portfolio - Mobile Gallery - Basic
	 *
	 * @return void
	 */
	public function mobile_gallery_basic() {
		return array(
			'title'       => __( 'Mobile Gallery', 'lsx-projects' ),
			'description' => __( 'A 4 column display for of your mobile screenshots.', 'lsx-projects' ),
			'categories'  => array( $this->pattern_category ),
			'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","right":"var:preset|spacing|x-small","left":"var:preset|spacing|x-small"}}},"backgroundColor":"custom-primary-white","layout":{"inherit":true,"type":"constrained"}} -->
			<div class="wp-block-group alignfull has-custom-primary-white-background-color has-background" style="margin-top:0px;padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--x-small);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--x-small)">

			<!-- wp:spacer {"height":"40px"} -->
			<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:heading {"textAlign":"center","align":"wide","className":"wp-block-heading","fontSize":"x-large"} -->
			<h2 class="alignwide has-text-align-center wp-block-heading has-x-large-font-size" id="h-mobile">Mobile</h2>
			<!-- /wp:heading -->
			
			<!-- wp:spacer {"height":"20px"} -->
			<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->
			
			<!-- wp:columns {"align":"wide"} -->
			<div class="wp-block-columns alignwide"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:image {"align":"center","id":37347,"sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2022/11/portfolio-mobile-savvy-1.jpg" alt="" class="wp-image-37347"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column -->
			
			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:image {"id":37350,"sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2022/11/portfolio-mobile-savvy-4.jpg" alt="" class="wp-image-37350"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column -->
			
			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:image {"align":"center","id":37348,"sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2022/11/portfolio-mobile-savvy-2.jpg" alt="" class="wp-image-37348"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column -->
			
			<!-- wp:column -->
			<div class="wp-block-column"><!-- wp:image {"align":"center","id":37349,"sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full"><img src="https://gutenberg.lightspeedwp.dev/wp-content/uploads/2022/11/portfolio-mobile-savvy-3.jpg" alt="" class="wp-image-37349"/></figure>
			<!-- /wp:image --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns --></div>
			<!-- /wp:group -->',
		);
	}

	/**
	 * Portfolio - Related Posts
	 *
	 * @return void
	 */
	public function related_portfolio() {
		return array(
			'title'       => __( 'Related Projects', 'lsx-projects' ),
			'description' => __( 'Displays the related portfolio based on the matching project category.', 'lsx-projects' ),
			'categories'  => array( $this->pattern_category ),
			'content'     => $this->get_related_portfolio_content(),
		);
	}

	private function get_related_portfolio_content() {
		$content = '
		<!-- wp:group {"tagName":"main","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"40px","bottom":"80px"}}},"backgroundColor":"base","className":"site-content","layout":{"type":"constrained"}} -->
			<main class="wp-block-group site-content has-base-background-color has-background" style="margin-top:0;padding-top:40px;padding-bottom:80px">
				<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
					<div class="wp-block-group alignwide">
						<!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"padding":{"bottom":"var:preset|spacing|x-small"}}},"fontSize":"max-48"} -->
							<h3 class="wp-block-heading alignwide has-max-48-font-size" style="padding-bottom:var(--wp--preset--spacing--x-small);font-style:normal;font-weight:700"><strong>' . __( 'Related Projects', 'lsx-projects' ) . '</strong></h3>
						<!-- /wp:heading -->
						
						<!-- wp:query {"queryId":5,"query":{"related":1,"perPage":2,"pages":0,"offset":0,"postType":"project","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"parents":[]},"displayLayout":{"type":"flex","columns":2},"align":"full","layout":{"type":"constrained"}} -->
							<div class="wp-block-query alignfull">
								<!-- wp:post-template -->
									<!-- wp:post-title /-->
									
									<!-- wp:post-excerpt /-->
								<!-- /wp:post-template -->
								
								<!-- wp:query-no-results -->
									<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
									<p></p>
									<!-- /wp:paragraph -->
								<!-- /wp:query-no-results -->
							</div>
						<!-- /wp:query -->
					</div>
				<!-- /wp:group -->
			</main>
		<!-- /wp:group -->';
		return $content;
	}

	/**
	 * A function to replace the query post vars.
	 *
	 * @param array $query
	 * @param WP_Block $block
	 * @param int $page
	 * @return array
	 */
	public function replace_related_vars( $query, $block, $page ) {
		if ( ! is_admin() && is_singular( 'project' ) && 'project' === $query['post_type'] && isset( $block->context['query']['related'] ) ) {
			$group     = array();
			$terms     = get_the_terms( get_the_ID(), 'project-group' );

			if ( is_array( $terms ) && ! empty( $terms ) ) {
				foreach( $terms as $term ) {
					$group[] = $term->term_id;
				}
			}
			$query['tax_query'] = array(
				array(
					'taxonomy' => 'project-group',
					'field'    => 'term_id',
					'terms'     => $group,
				)
			);
			$query['orderby']      = 'rand';
			$query['post__not_in'] = array( get_the_ID() );
		}
		return $query;
	}
}
