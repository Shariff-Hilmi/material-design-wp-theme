<?php
/**
 * Archive card layout
 */
?>

<div class="masonry-grid layout-masonry-2">
	<div class="masonry-grid_column">

	<?php
	$i = 1;
	$posts_per_page = get_option( 'posts_per_page' );
	$column_limit   = absint( round( $posts_per_page / 2 ) );

	/* Start the Loop */
	while ( have_posts() ) :
		the_post();

		/*
		* Include the Post-Type-specific template for the content.
		* If you want to override this in a child theme, then include a file
		* called content-___.php (where ___ is the Post Type name) and that will be used instead.
		*/
		get_template_part( 'template-parts/content', get_post_type() );

		if ( $column_limit === $i ) {
			echo '</div><div class="masonry-grid_column">';
		}

		$i++;

		endwhile;

		?>
	</div>
</div>