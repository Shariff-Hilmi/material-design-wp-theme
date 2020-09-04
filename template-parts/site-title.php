<?php
/**
 * The site title template
 * 
 * @package MaterialTheme
 */

$hide_site_title = get_theme_mod( 'material_header_title_display' );

if ( $hide_site_title ) {
	return;
}

if ( is_front_page() && is_home() ) :
	?>
	<h1 class="site-title mdc-typography mdc-typography--headline6">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
	</h1>
	<?php
else :
	?>
	<div class="site-title mdc-typography mdc-typography--headline6"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
	<?php
endif;
