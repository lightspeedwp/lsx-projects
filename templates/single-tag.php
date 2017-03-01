<?php
/**
 * Template Name: LSX Projects Full
 *
 * @package lsx-projects
*/

get_header(); ?>

	<?php lsx_content_wrap_before(); ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	<div class="content-area front-page col-sm-12">
	
		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main">

			<?php lsx_projects_single_tag(); ?>

			<?php lsx_content_bottom(); ?>
		
		</main><!-- #main -->
		
		<?php lsx_content_after(); ?>

	</div><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>

<?php get_footer();