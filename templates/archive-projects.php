<?php
/**
 * The template for displaying all single projects.
 *
 * @package lsx-projects
 */

get_header(); ?>

<?php lsx_content_wrap_before(); ?>

<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

	<?php lsx_content_before(); ?>

	<main id="main" class="site-main">

		<?php lsx_content_top(); ?>

		<?php
		if ( ! function_exists( 'lsx_search' ) ) {
			$args = array(
				'taxonomy'   => 'project-group',
				'hide_empty' => false,
			);

			$groups = get_terms( $args );
			$group_selected = get_query_var( 'project-group' );

			if ( count( $groups ) > 0 ) {
			?>

			<ul class="nav nav-tabs lsx-projects-filter">
				<?php
					$group_selected_class = '';

					if ( empty( $group_selected ) ) {
						$group_selected_class = ' class="active"';
					}
				?>

				<li<?php echo wp_kses_post( $group_selected_class ); ?>><a href="<?php echo empty( $group_selected ) ? '#' : esc_url( get_post_type_archive_link( 'project' ) ); ?>" data-filter="*"><?php esc_html_e( 'All', 'lsx-projects' ); ?></a></li>

				<?php foreach ( $groups as $group ) : ?>
					<?php
						$group_selected_class = '';

						if ( (string) $group_selected === (string) $group->slug ) {
							$group_selected_class = ' class="active"';
						}
					?>

					<li<?php echo wp_kses_post( $group_selected_class ); ?>><a href="<?php echo empty( $group_selected ) ? '#' : esc_url( get_term_link( $group ) ); ?>" data-filter=".filter-<?php echo esc_attr( $group->slug ); ?>"><?php echo esc_attr( $group->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>

			<?php
			}
		}
		?>

		<?php if ( have_posts() ) : ?>

			<div class="lsx-projects-container">
				<div class="row row-flex lsx-projects-row">

					<?php
						$count = 0;

						while ( have_posts() ) {
							the_post();
							include LSX_PROJECTS_PATH . '/templates/content-archive-projects.php';
						}
					?>

				</div>
			</div>

			<?php lsx_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'partials/content', 'none' ); ?>

		<?php endif; ?>

		<?php lsx_content_bottom(); ?>

	</main><!-- #main -->

	<?php lsx_content_after(); ?>

</div><!-- #primary -->

<?php lsx_content_wrap_after(); ?>

<?php get_sidebar(); ?>

<?php get_footer();
