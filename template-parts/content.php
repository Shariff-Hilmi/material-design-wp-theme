<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package MaterialTheme
 */

$show_comments = material_get_theme_mod( 'archive_comments', true );
$show_author   = material_get_theme_mod( 'archive_author', true );
$show_excerpt  = material_get_theme_mod( 'archive_excerpt', true );
$show_date     = material_get_theme_mod( 'archive_date', true );
$classes       = material_get_theme_mod( 'archive_outlined', false ) ? 'mdc-card--outlined' : '';
?>

<div id="<?php the_ID(); ?>" <?php post_class( "mdc-card post-card $classes" ); ?>>
	<a class="mdc-card__link" href="<?php the_permalink(); ?>">
		<div class="mdc-card__primary-action post-card__primary-action" tabindex="0">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="mdc-card__media mdc-card__media--16-9 post-card__media" style="background-image: url(&quot;<?php echo esc_attr( get_the_post_thumbnail_url() ); ?>&quot;);"></div>
			<?php endif; ?>
			<div class="post-card__primary">
				<?php the_title( '<h2 class="post-card__title mdc-typography mdc-typography--headline6">', '</h2>' ); ?>

				<?php if ( ! empty( $show_date ) ) : ?>
					<h3 class="post-card__subtitle mdc-typography mdc-typography--subtitle2"><?php the_time( 'F j, Y' ); ?></h3>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $show_excerpt ) ) : ?>
				<div class="post-card__secondary mdc-typography mdc-typography--body2"><?php the_excerpt(); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $show_author ) || ! empty( $show_comments ) ) : ?>
			<div class="mdc-card__actions">
				<div class="mdc-card__action-buttons">
					<?php if ( ! empty( $show_author ) ) : ?>
						<a
							class="mdc-button mdc-card__action mdc-card__action--button"
							href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
							aria-label="
								<?php
								printf(
									/* translators: 1: author name. */
									esc_attr__(
										'Author: %s',
										'material-theme'
									),
									esc_attr( get_the_author() )
								);
								?>
							"
						>
							<span class="mdc-button__ripple"></span>
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 18 ); ?>
							<?php the_author(); ?>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $show_comments ) ) : ?>
						<a href="<?php comments_link(); ?>" class="mdc-button mdc-card__action mdc-card__action--button">
							<span class="mdc-button__ripple"></span>
							<i class="material-icons mdc-button__icon" aria-hidden="true">comment</i>
							<?php
							echo esc_html(
								sprintf(
									/* translators: 1: comment count. */
									_nx( // phpcs:disable
										'One Comment', // phpcs:disable
										'%s Comments',
										get_comments_number(),
										'comments title',
										'material-theme'
									),
									number_format_i18n( get_comments_number() )
								)
							);
							?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</a>
</div>
