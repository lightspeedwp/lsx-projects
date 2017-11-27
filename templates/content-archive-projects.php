<?php
/**
 * @package lsx-projects
 */
?>

<?php
	global $lsx_projects_frontend;

	$groups = '';
	$groups_class = '';
	$terms = get_the_terms( get_the_ID(), 'project-group' );

	if ( $terms && ! is_wp_error( $terms ) ) {
		$groups = array();
		$groups_class = array();

		foreach ( $terms as $term ) {
			$groups[] = '<a href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
			$groups_class[] = 'filter-' . $term->slug;
		}

		$groups = join( ', ', $groups );
		$groups_class = join( ' ', $groups_class );
	}
?>

<div class="col-xs-12 col-sm-6 col-md-4 lsx-projects-column <?php echo esc_attr( $groups_class ); ?>">
	<article class="lsx-projects-slot">
		<?php if ( ! empty( lsx_get_thumbnail( 'lsx-thumbnail-single' ) ) ) : ?>
			<?php if ( ! isset( $lsx_projects_frontend->options['display'] ) || ! $lsx_projects_frontend->options['display']['team_disable_single'] ) : ?>
				<a href="<?php the_permalink(); ?>"><figure class="lsx-projects-avatar"><?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?></figure></a>
			<?php else : ?>
				<figure class="lsx-projects-avatar"><?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?></figure>
			<?php endif; ?>
		<?php endif; ?>

		<h5 class="lsx-projects-title">
			<?php if ( ! isset( $lsx_projects_frontend->options['display'] ) || ! $lsx_projects_frontend->options['display']['team_disable_single'] ) : ?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
		</h5>

		<?php if ( ! empty( $groups ) ) : ?>
			<p class="lsx-projects-groups"><?php echo wp_kses_post( $groups ); ?></p>
		<?php endif; ?>

		<!--<div class="lsx-projects-content"><?php the_excerpt(); ?></div>-->
		<div class="lsx-projects-content"><a href="<?php the_permalink(); ?>" class="moretag"><?php esc_html_e( 'View more', 'lsx-projects' ); ?></a></div>
	</article>
</div>
