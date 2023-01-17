<?php
/**
 * The template for displaying a single project.
 *
 * @package lsx-projects
 */

get_header(); ?>

<?php do_action( 'lsx_content_wrap_before' ); ?>

<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

	<?php do_action( 'lsx_content_before' ); ?>

	<main id="main" class="site-main">

		<?php do_action( 'lsx_content_top' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php include( LSX_PROJECTS_PATH . '/templates/content-single-projects.php' ); ?>

			<?php endwhile; ?>

		<?php endif; ?>

		<?php do_action( 'lsx_content_bottom' ); ?>

	</main><!-- #main -->

	<?php do_action( 'lsx_content_after' ); ?>

</div><!-- #primary -->

<?php do_action( 'lsx_content_wrap_after' ); ?>

<?php get_sidebar(); ?>

<?php get_footer();
