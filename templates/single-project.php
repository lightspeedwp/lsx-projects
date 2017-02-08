<?php
/**
 * Template Name: LSX Projects Full
 *
 * @package lsx-projects
*/

get_header(); ?>

	<?php lsx_content_wrap_before(); ?>

	<div id="primary" class="content-area front-page col-sm-12">
	
		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main" style="margin-top:100px">

			<?php lsx_content_top(); ?>

			<?php lsx_projects_list(); ?>

			<?php lsx_content_bottom(); ?>
		
		</main><!-- #main -->
		
		<?php lsx_content_after(); ?>

	</div><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>

<?php get_footer();