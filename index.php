<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package MaterialTheme
 */

get_header();

$max_width  = material_get_theme_mod( 'archive_width', 'normal' );
$class_name = sprintf( 'material-archive__%s', $max_width );
?>

	<div id="primary" class="content-area <?php echo esc_attr( $class_name ); ?>">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;
			?>

			<div class="site-main__inner">
				<?php get_template_part( 'template-parts/archive' ); ?>
			</div>
		</div>

			<?php
			get_template_part( 'template-parts/page-navigation' );

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>

<?php
get_footer();
