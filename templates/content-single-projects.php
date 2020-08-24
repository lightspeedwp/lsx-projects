<?php
/**
 * @package lsx-projects
 */
?>

<?php
	$client = get_post_meta( get_the_ID(), 'lsx_project_client', true );
	$client_logo = get_post_meta( get_the_ID(), 'lsx_project_client_logo', true );
	$url = get_post_meta( get_the_ID(), 'lsx_project_url', true );

	global $lsx_projects;

	$button_label = '';
	$button_cf_id = '';

	if ( ! empty( projects_get_option( 'projects_modal_enable' ) ) ) {
		if ( ! empty( projects_get_option( 'projects_modal_cta_label' ) ) && ! empty( projects_get_option( 'projects_modal_form_id' ) ) ) {
			$button_label = projects_get_option( 'projects_modal_cta_label' );
			$button_cf_id = projects_get_option( 'projects_modal_form_id' );
		}
	}

	if ( ! empty( $client_logo ) ) {
		$client_logo = '<img src="' . $client_logo . '">';
	}

	$groups = '';
	$terms = get_the_terms( get_the_ID(), 'project-group' );

	if ( $terms && ! is_wp_error( $terms ) ) {
		$groups = array();

		foreach ( $terms as $term ) {
			$groups[] = '<a href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
		}

		$groups = join( ', ', $groups );
	}

	// Connections

	$connections = array();

	// Connection Projects

	if ( $terms && ! is_wp_error( $terms ) ) {
		$groups_ = array();

		foreach ( $terms as $term ) {
			$groups_[] = $term->term_id;
		}

		if ( count( $groups_ ) > 0 ) {
			$connection_project['post_type'] = 'project';
			$connection_project['title'] = esc_html__( 'Related Projects', 'lsx-projects' );
			//$connection_project['posts'] = get_post_meta( get_the_ID(), 'project_to_project', false );
			$connection_project['posts'] = array();

			$args = array(
				'post_type'              => 'project',
				'post__not_in'           => array( get_the_ID() ),
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => 1,
				'update_post_meta_cache' => false,
				'tax_query' => array(
					array(
						'taxonomy' => 'project-group',
						'terms'    => $groups_,
					),
				),
			);

			$projects_ = new \WP_Query( $args );

			if ( $projects_->have_posts() ) {
				while ( $projects_->have_posts() ) {
					$projects_->the_post();
					$connection_project['posts'][] = get_the_ID();
					wp_reset_postdata();
				}
			}

			if ( ! empty( $connection_project['posts'] ) ) {
				$post_ids = join( ',', $connection_project['posts'] );
				$connection_project['shortcode'] = '[lsx_projects columns="3" include="' . $post_ids . '"]';
				$connections[] = $connection_project;
			}
		}
	}

	// Connection Products

	if ( class_exists( 'WooCommerce' ) ) {
		$connection_product['post_type'] = 'product';
		$connection_product['title'] = esc_html__( 'Related Products', 'lsx-projects' ) . ' <small>' . esc_html__( 'Products used to build this project', 'lsx-projects' ) . '</small>';
		$connection_product['posts'] = get_post_meta( get_the_ID(), 'product_to_project', false );

		if ( ! empty( $connection_product['posts'] ) ) {
			$connection_product['small_list_html'] = '';

			$args = array(
				'post_type'              => 'product',
				'post__in'               => $connection_product['posts'][0],
				'orderby'                => 'post__in',
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => 1,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);

			$connection_product['posts_obj'] = new \WP_Query( $args );

			if ( $connection_product['posts_obj']->have_posts() ) {
				$connection_product['small_list_html'] = array();

				while ( $connection_product['posts_obj']->have_posts() ) {
					$connection_product['posts_obj']->the_post();
					$connection_product['small_list_html'][] = '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a>';
					wp_reset_postdata();
				}

				$connection_product['posts_obj']->rewind_posts();
				$connection_product['small_list_html'] = join( ', ', $connection_product['small_list_html'] );
			}

			$connections[] = $connection_product;
		}
	}

	// Connection Services

	$connection_service['post_type'] = 'page';
	// $connection_service['title'] = esc_html__( 'Services', 'lsx-projects' );
	$connection_service['pages'] = get_post_meta( get_the_ID(), 'page_to_project', false );

	if ( ! empty( $connection_service['pages'] ) ) {
		$post_ids = join( ',', $connection_service['pages'] );
		//$connection_service['shortcode'] = '[lsx_services columns="3" include="' . $post_ids . '"]';
		$connection_service['small_list_html'] = '';

		$args = array(
			'post_type'              => 'page',
			'post__in'               => $connection_service['pages'],
			'orderby'                => 'post__in',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$services_ = new \WP_Query( $args );

		if ( $services_->have_posts() ) {
			$connection_service['small_list_html'] = array();

			while ( $services_->have_posts() ) {
				$services_->the_post();
				$connection_service['small_list_html'][] = '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a>';
				wp_reset_postdata();
			}

			$connection_service['small_list_html'] = join( ', ', $connection_service['small_list_html'] );
		}

		$connections[] = $connection_service;
	}

	// Connection Testimonials

	$connection_testimonial['post_type'] = 'testimonial';
	// $connection_testimonial['title'] = esc_html__( 'Testimonials', 'lsx-projects' );
	$connection_testimonial['posts'] = get_post_meta( get_the_ID(), 'testimonial_to_project', false );

	if ( ! empty( $connection_testimonial['posts'] ) ) {
		$post_ids = join( ',', $connection_testimonial['posts'][0] );
		$connection_testimonial['shortcode'] = '[lsx_testimonials columns="1" include="' . $post_ids . '" orderby="date" order="DESC"]';
		$connections[] = $connection_testimonial;
	}

	// Connection Team

	$connection_team['post_type'] = 'team';
	// $connection_team['title'] = esc_html__( 'Team', 'lsx-projects' );
	$connection_team['posts'] = get_post_meta( get_the_ID(), 'team_to_project', false );

	if ( ! empty( $connection_team['posts'] ) ) {
		$post_ids = join( ',', $connection_team['posts'][0] );
		$connection_team['shortcode'] = '[lsx_team columns="4" include="' . $post_ids . '" show_social="false" show_desc="false" show_link="true"]';
		$connection_team['small_list_html'] = '';

		$args = array(
			'post_type'              => 'team',
			'post__in'               => $connection_team['posts'][0],
			'orderby'                => 'post__in',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$team_ = new \WP_Query( $args );

		if ( $team_->have_posts() ) {
			global $lsx_team;
			$connection_team['small_list_html'] = array();

			while ( $team_->have_posts() ) {
				$team_->the_post();
				$connection_team['small_list_html'][] = '<a href="' . get_permalink() . '">' . $lsx_team->get_thumbnail( get_the_ID(), 'lsx-team-archive' ) . '</a>';
				wp_reset_postdata();
			}

			$connection_team['small_list_html'] = join( ' ', $connection_team['small_list_html'] );
		}

		$connections[] = $connection_team;
	}
?>

<?php lsx_entry_before(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php lsx_entry_top(); ?>

	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-8">
			<div class="entry-content"><?php the_content(); ?></div>

			<?php if ( count( $connections ) > 0 ) : ?>
				<?php foreach ( $connections as $i => $connection ) : ?>
					<?php
						if ( 'testimonial' === $connection['post_type'] ) {
							echo '<div class="tab-pane-fake">';
							echo do_shortcode( $connection['shortcode'] );
							echo '</div>';
						}
					?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="col-xs-12 col-sm-5 col-md-4">
			<div class="entry-fixed-sidebar-wrapper">
				<div class="entry-fixed-sidebar">
					<?php if ( ! empty( $client_logo ) ) : ?>
						<div class="entry-meta-single"><?php echo wp_kses_post( $client_logo ); ?></div>
					<?php elseif ( ! empty( $client ) ) : ?>
						<div class="entry-meta-single"><?php echo esc_html( $client ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $groups ) ) : ?>
						<div class="entry-meta-key"><?php esc_html_e( 'Industry:', 'lsx-projects' ); ?></div>
						<div class="entry-meta-value"><?php echo wp_kses_post( $groups ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $connection_service['small_list_html'] ) ) : ?>
						<div class="entry-meta-key"><?php esc_html_e( 'Services:', 'lsx-projects' ); ?></div>
						<div class="entry-meta-value"><?php echo wp_kses_post( $connection_service['small_list_html'] ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $connection_product['small_list_html'] ) ) : ?>
						<!--
						<div class="entry-meta-key"><?php esc_html_e( 'Products used:', 'lsx-projects' ); ?></div>
						<div class="entry-meta-value"><?php echo wp_kses_post( $connection_product['small_list_html'] ); ?></div>
						-->
					<?php endif; ?>

					<?php if ( ! empty( $connection_team['small_list_html'] ) ) : ?>
						<div class="entry-meta-key"><?php esc_html_e( 'Team members involved:', 'lsx-projects' ); ?></div>
						<div class="entry-meta-value entry-meta-value-team"><?php echo wp_kses_post( $connection_team['small_list_html'] ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $url ) ) : ?>
						<div class="entry-meta-single"><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="nofollow" class="btn btn-block secondary-btn"><?php esc_html_e( 'See website', 'lsx-projects' ); ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
					<?php endif; ?>

					<?php if ( ! empty( $button_label ) ) : ?>
						<div class="entry-meta-single"><a href="#lsx-project-contact" data-toggle="modal" class="btn btn-block cta-btn"><?php echo esc_html( $button_label ); ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ( count( $connections ) > 0 ) : ?>
		<?php foreach ( $connections as $i => $connection ) : ?>
			<?php
				// Team is now visible on detail box
				// Services is now visible on detail box
				// Testimonials is now visible below the content
				if ( in_array( $connection['post_type'], array( 'team', 'testimonial', 'service' ) ) ) {
					continue;
				}
			?>
			<?php if ( 'page' !== $connection['post_type'] ) { ?>
				<div class="lsx-projects-section lsx-full-width">
					<div class="row">
						<div class="col-xs-12">
							<h3 class="lsx-title"><?php echo wp_kses_post( $connection['title'] ); ?></h3>

							<?php
								if ( 'product' === $connection['post_type'] ) {

									if ( $connection_product['posts_obj']->have_posts() ) {
										// @codingStandardsIgnoreLine
										echo apply_filters( 'woocommerce_before_widget_product_list', '<ul class="product_list_widget">' );

										while ( $connection_product['posts_obj']->have_posts() ) {
											$connection_product['posts_obj']->the_post();
											wc_get_template( 'content-widget-product.php', array(
												'show_rating' => false,
											) );

											wp_reset_postdata();
										}

										// @codingStandardsIgnoreLine
										echo apply_filters( 'woocommerce_after_widget_product_list', '</ul>' );
									}
								} else {
									echo do_shortcode( $connection['shortcode'] );
								}
							?>
						</div>
					</div>
				</div>
			<?php
			}
		?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php
	// Connection Alt Produtcs

	$connection_alt_product['posts'] = get_post_meta( get_the_ID(), 'lsx_project__alt_products', false );

	if ( ! empty( $connection_alt_product['posts'] ) ) {
		?>
		<div class="lsx-projects-section lsx-full-width">
			<div class="row">
				<div class="col-xs-12">
					<h3 class="lsx-title"><?php echo esc_html__( 'Related Products', 'lsx-projects' ); ?></h3>
					<div id="#lsx-alt-products-slider" class="lsx-alt-products lsx-projects-shortcode slick-slider" data-slick="{'slidesToShow': 3, 'slidesToScroll': 3 }">
					<?php
					foreach ( $connection_alt_product['posts'][0] as $alt_post ) {
						$alt_post_name    = $alt_post['lsx_project_alt_product_title'];
						$alt_post_link    = $alt_post['lsx_project_alt_product_link'];
						$alt_post_img_id  = $alt_post['lsx_project_alt_product_image_id'];
						$alt_post_img_url = $alt_post['lsx_project_alt_product_image'];

						?>
						<div class="lsx-projects-slot">
							<a href="<?php echo $alt_post_link; ?>">
								<figure class="lsx-projects-avatar">
									<img src="<?php echo esc_url( $alt_post_img_url ); ?>" alt="<?php echo esc_html( $alt_post_name ); ?>">
								</figure>
							</a>
							<h5 class="lsx-projects-title">
								<a href="<?php echo esc_url( $alt_post_link ); ?>">
									<?php echo esc_html( $alt_post_name ); ?>
								</a>
							</h5>
							<div class="lsx-projects-content">
								<a class="moretag" href="<?php echo esc_url( $alt_post_link ); ?>">
									<?php echo esc_html__( 'View more', 'lsx-projects' ); ?>
								</a>
							</div>
						</div>
						<?php
					}
					?>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>


	<?php lsx_entry_bottom(); ?>

</article><!-- #post-## -->

<?php lsx_entry_after();
