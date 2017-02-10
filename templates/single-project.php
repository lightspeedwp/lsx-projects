<?php
/**
 * Template Name: LSX Projects Full
 *
 * @package lsx-projects
 */

get_header(); ?>

<?php lsx_content_wrap_before(); ?>

    <div class="content-area front-page col-sm-8">

        <?php lsx_content_before(); ?>

        <main id="main" class="site-main">

            <?php lsx_content_top(); ?>

            <?php lsx_projects_list(); ?>

            <?php lsx_content_bottom(); ?>

        </main><!-- #main -->

        <?php lsx_content_after(); ?>

    </div><!-- #primary -->

<?php lsx_content_wrap_after(); ?>

    <div class="content-area front-page col-sm-4" style="margin-top: -40px;">

        <?php lsx_projects_sidebar(); ?>

    </div>

<?php get_footer();