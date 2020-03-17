<?php
/**
 * Template part for displaying top app bar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Material-theme-wp
 */

?>

<header class="mdc-top-app-bar">
	<div class="mdc-top-app-bar__row">
		<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
		<button class="material-icons mdc-top-app-bar__navigation-icon mdc-icon-button"><?php esc_html_e( 'menu', 'material-theme-wp' ); ?></button>
		<span class="mdc-top-app-bar__title">
			<?php
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			?>
		</span>
		</section>
		<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'walker'         => new Material_Theme_Menu(),
				'container'      => '',
				'items_wrap'     => '%3$s',
			) );
			?>
		</section>
	</div>
</header>