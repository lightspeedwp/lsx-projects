<?php
/**
 * Template Name: LSX Projects Full
 *
 * @package lsx-projects
*/

get_header(); ?>

	<?php lsx_content_wrap_before(); ?>
	<style>
		#filterNav{
			background-color: #f2f2f2;
			height: 50px;
			margin-top:0px;
		}
		#filterNav li a:hover, #filterNav li a.selected{
			background-color: #418ad0;
			color:white;
		}
		#filterNav li a:hover, #filterNav li a.selected:hover{
			background-color: #418ad0;
			color:white;
		}
		#filterNav li{
			color: #418ad0;
			padding: 4px;
			margin-left: 10px;
			padding-top: 8px;
		}
		#filterNav li a{
			background: initial;
			border-radius: 0px;
			color: #418ad0;
			padding-bottom: 13px;
			padding-top: 13px;
			padding-left: 25px;
			padding-right: 25px;
			text-decoration: underline !important;
		}
		#filterNav li a:hover{
			background: initial;
			border-radius: 0px;
			color: #418ad0;
		}
		.altBtn{
			background: #418ad0;
			color:white;
		}
		.project-image{
			height: 240px;
			width: 380px;
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
		}
	</style>

	<div class="content-area front-page col-sm-12" style="margin-top: 160px">
	
		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main">

			<?php lsx_content_top(); ?>

			<?php lsx_groups_list();?>

			<?php lsx_projects_list(); ?>

			<?php lsx_content_bottom(); ?>
		
		</main><!-- #main -->
		
		<?php lsx_content_after(); ?>

	</div><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>

<?php get_footer();