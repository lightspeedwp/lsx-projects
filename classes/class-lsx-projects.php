<?php
/**
 * LSX Projects Main Class
 *
 * @package   LSX Projects
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Projects {

	public $columns, $responsive, $options;

	public function __construct() {
		$this->options = projects_get_options();

		add_filter( 'lsx_banner_allowed_post_types', array( $this, 'lsx_banner_allowed_post_types' ) );
		add_filter( 'lsx_banner_allowed_taxonomies', array( $this, 'lsx_banner_allowed_taxonomies' ) );
	}

	/**
	 * Enable project custom post type on LSX Banners.
	 */
	public function lsx_banner_allowed_post_types( $post_types ) {
		$post_types[] = 'project';
		return $post_types;
	}

	/**
	 * Enable project custom taxonomies on LSX Banners.
	 */
	public function lsx_banner_allowed_taxonomies( $taxonomies ) {
		$taxonomies[] = 'project-group';
		return $taxonomies;
	}

	/**
	 * Returns the shortcode output markup
	 */
	public function output( $atts ) {
		// @codingStandardsIgnoreLine
		extract( shortcode_atts( array(
			'columns' => 3,
			'orderby' => 'name',
			'order' => 'ASC',
			'limit' => '-1',
			'include' => '',
			'display' => 'excerpt',
			'size' => 'lsx-thumbnail-single',
			'responsive' => 'true',
			'show_image' => 'true',
			'carousel' => 'true',
			'featured' => 'false',
		), $atts ) );

		$output = '';

		if ( 'true' === $responsive || true === $responsive ) {
			$responsive = ' img-responsive';
		} else {
			$responsive = '';
		}

		$this->columns = $columns;
		$this->responsive = $responsive;

		if ( ! empty( $include ) ) {
			$include = explode( ',', $include );

			$args = array(
				'post_type' => 'project',
				'posts_per_page' => $limit,
				'post__in' => $include,
				'orderby' => 'post__in',
				'order' => $order,
			);
		} else {
			$args = array(
				'post_type' => 'project',
				'posts_per_page' => $limit,
				'orderby' => $orderby,
				'order' => $order,
			);

			if ( 'true' === $featured || true === $featured ) {
				$args['meta_key'] = 'lsx_project_featured';
				$args['meta_value'] = 1;
			}
		}

		$projects = new \WP_Query( $args );

		if ( $projects->have_posts() ) {
			global $post;

			$count = 0;
			$count_global = 0;

			if ( 'true' === $carousel || true === $carousel ) {
				$output .= "<div id='lsx-projects-slider' class='lsx-projects-shortcode' data-slick='{\"slidesToShow\": $columns, \"slidesToScroll\": $columns }'>";
			} else {
				$output .= "<div class='lsx-projects-shortcode'><div class='row'>";
			}

			while ( $projects->have_posts() ) {
				$projects->the_post();

				// Count
				$count++;
				$count_global++;

				// Content
				if ( 'full' === $display ) {
					$content = apply_filters( 'the_content', get_the_content() );
					$content = str_replace( ']]>', ']]&gt;', $content );
				} elseif ( 'excerpt' === $display ) {
					$content = apply_filters( 'the_excerpt', get_the_excerpt() );
				}

				// Image
				if ( 'true' === $show_image || true === $show_image ) {
					if ( is_numeric( $size ) ) {
						$thumb_size = array( $size, $size );
					} else {
						$thumb_size = $size;
					}

					if ( ! empty( get_the_post_thumbnail( $post->ID ) ) ) {
						$image = get_the_post_thumbnail( $post->ID, $thumb_size, array(
							'class' => $responsive,
						) );
					} else {
						$image = '';
					}

					if ( empty( $image ) ) {
						if ( ! empty( $this->options['display']['projects_placeholder'] ) ) {
							$image = '<img class="' . $responsive . '" src="' . $this->options['display']['projects_placeholder'] . '" width="' . $size . '" alt="placeholder" />';
						} else {
							$image = '';
						}
					}
				} else {
					$image = '';
				}

				// Project groups
				$groups = '';
				$terms = get_the_terms( $post->ID, 'project-group' );

				if ( $terms && ! is_wp_error( $terms ) ) {
					$groups = array();

					foreach ( $terms as $term ) {
						$groups[] = $term->name;
					}

					$groups = join( ', ', $groups );
				}

				$project_groups = '' !== $groups ? "<p class='lsx-projects-groups'>$groups</p>" : '';

				if ( 'true' === $carousel || true === $carousel ) {
					$output .= "
						<div class='lsx-projects-slot'>
							" . ( ! empty( $image ) ? "<a href='" . get_permalink() . "'><figure class='lsx-projects-avatar'>$image</figure></a>" : '' ) . "
							<h5 class='lsx-projects-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . "</a></h5>
							$project_groups
							<div class='lsx-projects-content'><a href='" . get_permalink() . "' class='moretag'>" . esc_html__( 'View more', 'lsx-projects' ) . '</a></div>
						</div>';
				} elseif ( $columns >= 1 && $columns <= 4 ) {
					$md_col_width = 12 / $columns;

					$output .= "
						<div class='col-xs-12 col-md-" . $md_col_width . "'>
							<div class='lsx-projects-slot'>
								" . ( ! empty( $image ) ? "<a href='" . get_permalink() . "'><figure class='lsx-projects-avatar'>$image</figure></a>" : '' ) . "
								<h5 class='lsx-projects-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . "</a></h5>
								$project_groups
								<div class='lsx-projects-content'><a href='" . get_permalink() . "' class='moretag'>" . esc_html__( 'View more', 'lsx-projects' ) . '</a></div>
							</div>
						</div>';

					if ( $count == $columns && $projects->post_count > $count_global ) {
						$output .= '</div>';
						$output .= "<div class='row'>";
						$count = 0;
					}
				} else {
					$output .= "
						<p class='bg-warning' style='padding: 20px;'>
							" . esc_html__( 'Invalid number of columns set. LSX Projects supports 1 to 4 columns.', 'lsx-projects' ) . '
						</p>';
				}

				wp_reset_postdata();
			}

			if ( 'true' !== $carousel && true !== $carousel ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}
	}

}

global $lsx_projects;
$lsx_projects = new LSX_Projects();
