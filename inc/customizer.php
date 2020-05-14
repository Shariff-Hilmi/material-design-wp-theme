<?php
/**
 * Material-theme-wp Theme Customizer
 *
 * @package MaterialTheme
 */

namespace MaterialTheme\Customizer;

use MaterialTheme\Customizer\Content;
use MaterialTheme\Customizer\Header;
use MaterialTheme\Customizer\Footer;

/**
 * Attach hooks.
 *
 * @return void
 */
function setup() {
	add_action( 'customize_register', __NAMESPACE__ . '\register' );
	add_action( 'customize_preview_init', __NAMESPACE__ . '\preview_scripts' );

	add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\scripts' );

	add_action( 'wp_head', __NAMESPACE__ . '\frontend_inline_css', 2 );
	add_action( 'admin_head', __NAMESPACE__ . '\frontend_inline_css', 2 );
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function register( $wp_customize ) {
	require get_template_directory() . '/inc/customizer/controls/class-radio-toggle-control.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => __NAMESPACE__ . '\get_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => __NAMESPACE__ . '\get_description',
			)
		);
	}
}

/**
 * Define settings prefix.
 *
 * @return string Settings prefix.
 */
function get_slug() {
	return 'material';
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function get_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function get_description() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @return void
 */
function preview_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'material-theme-customizer-preview',
		get_template_directory_uri() . '/assets/js/customize-preview.js',
		[ 'customize-preview' ],
		$theme_version,
		true
	);

	$controls = array_merge( Content\get_color_controls(), Footer\get_color_controls() );
	$css_vars = [];

	if ( ! class_exists( 'MaterialThemeBuilder\Plugin' ) ) {
		$controls = array_merge( $controls, Header\get_color_controls() );
	}

	foreach ( $controls as $control ) {
		$css_vars[ prepend_slug( $control['id'] ) ] = $control['css_var'];
	}

	wp_localize_script(
		'material-theme-customizer-preview',
		'materialThemeColorControls',
		$css_vars
	);
}

/**
 * Enqueue control scripts.
 *
 * @return void
 */
function scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'material-theme-customizer-styles',
		get_template_directory_uri() . '/assets/css/customize-controls-compiled.css',
		[ 'wp-color-picker' ],
		$theme_version
	);

	wp_enqueue_script(
		'material-theme-customizer-controls',
		get_template_directory_uri() . '/assets/js/customize-controls.js',
		[ 'wp-color-picker', 'customize-controls' ],
		$theme_version,
		true
	);
}

/**
 * Register setting in customizer.
 *
 * @param  mixed $wp_customize Theme Customizer object.
 * @param  mixed $settings     Settings to register in customizer.
 * @return void
 */
function add_settings( $wp_customize, $settings = [] ) {
	$slug = get_slug();

	foreach ( $settings as $id => $setting ) {
		$id = prepend_slug( $id );

		if ( is_array( $setting ) ) {
			$defaults = [
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
				'default'           => get_default( $id ),
			];

			$setting = array_merge( $defaults, $setting );
		}

		/**
		 * Filters the customizer setting args.
		 *
		 * This allows other plugins/themes to change the customizer setting args.
		 *
		 * @param array   $setting Array of setting args.
		 * @param string  $id      ID of the setting.
		 */
		$setting = apply_filters( $slug . '_customizer_setting_args', $setting, $id );

		if ( is_array( $setting ) ) {
			$wp_customize->add_setting(
				$id,
				$setting
			);
		} elseif ( $setting instanceof \WP_Customize_Setting ) {
			$setting->id = $id;
			$wp_customize->add_setting( $setting );
		}
	}
}

/**
 * Prepend the slug name if it does not exist.
 *
 * @param  string $name The name of the setting/control.
 * @return string
 */
function prepend_slug( $name ) {
	$slug = get_slug();

	return false === strpos( $name, "{$slug}_" ) ? "{$slug}_{$name}" : $name;
}

/**
 * Get default value for a setting.
 *
 * @param  string $setting Name of the setting.
 * @return mixed
 */
function get_default( $setting ) {
	$slug     = get_slug();
	$setting  = str_replace( "{$slug}_", '', $setting );
	$defaults = get_default_values();

	return isset( $defaults[ $setting ] ) ? $defaults[ $setting ] : '';
}

/**
 * Set default values.
 *
 * @return array
 */
function get_default_values() {
	return [
		'header_background_color' => '#6200ee',
		'header_text_color'       => '#ffffff',
		'background_color'        => '#ffffff',
		'background_text_color'   => '#000000',
		'footer_background_color' => '#ffffff',
		'footer_text_color'       => '#000000',
		'archive_layout'          => 'card',
		'header_width_layout'     => 'boxed',
	];
}

/**
 * Add controls to customizer.
 *
 * @param  WP_Customize $wp_customize WP_Customize instance.
 * @param  array        $controls Array of controls to add to customizer.
 * @return void
 */
function add_controls( $wp_customize, $controls = [] ) {
	$slug = get_slug();

	foreach ( $controls as $id => $control ) {
		$id = prepend_slug( $id );

		/**
		 * Filters the customizer control args.
		 *
		 * This allows other plugins/themes to change the customizer controls args.
		 *
		 * @param array  $control Array of control args.
		 * @param string $id      ID of the control.
		 */
		$control = apply_filters( $slug . '_customizer_control_args', $control, $id );

		if ( is_array( $control ) ) {
			$wp_customize->add_control(
				$id,
				$control
			);
		} elseif ( $control instanceof \WP_Customize_Control ) {
			$control->id      = $id;
			$control->section = isset( $control->section ) ? $control->section : '';
			$wp_customize->add_control( $control );
		}
	}
}

/**
 * Add color controls to customizer.
 * Use `Material_Color_Palette_Control` if the material plugin is active.
 *
 * @param  WP_Customize $wp_customize   WP_Customize instance.
 * @param  array        $color_controls Array of controls to add to customizer.
 * @param  string       $section Section to add the controls to.
 * @return void
 */
function add_color_controls( $wp_customize, $color_controls, $section ) {
	/**
	 * Generate list of all the controls in the colors section.
	 */
	$controls = [];

	$section = prepend_slug( $section );

	foreach ( $color_controls as $control ) {
		if ( class_exists( 'MaterialThemeBuilder\Customizer\Material_Color_Palette_Control' ) ) {
			$controls[ $control['id'] ] = new \MaterialThemeBuilder\Customizer\Material_Color_Palette_Control(
				$wp_customize,
				prepend_slug( $control['id'] ),
				[
					'label'                => $control['label'],
					'section'              => $section,
					'related_text_setting' => ! empty( $control['related_text_setting'] ) ? $control['related_text_setting'] : false,
					'related_setting'      => ! empty( $control['related_setting'] ) ? $control['related_setting'] : false,
					'css_var'              => $control['css_var'],
					'a11y_label'           => ! empty( $control['a11y_label'] ) ? $control['a11y_label'] : '',
				]
			);
		} else {
			$controls[ $control['id'] ] = [
				'label'   => $control['label'],
				'section' => $section,
				'type'    => 'color',
			];
		}
	}

	add_controls( $wp_customize, $controls );
}

/**
 * Get custom frontend CSS based on the customizer theme settings.
 */
function get_frontend_css() {
	$color_vars = [];
	$controls   = array_merge( Content\get_color_controls(), Footer\get_color_controls() );
	$defaults   = get_default_values();

	if ( ! class_exists( 'MaterialThemeBuilder\Plugin' ) ) {
		$controls = array_merge( $controls, Header\get_color_controls() );
	}

	foreach ( $controls as $control ) {
		$default      = isset( $defaults[ $control['id'] ] ) ? $defaults[ $control['id'] ] : '';
		$value        = get_theme_mod( prepend_slug( $control['id'] ), $default );
		$color_vars[] = sprintf( '%s: %s;', esc_html( $control['css_var'] ), esc_html( $value ) );

		if ( '--mdc-theme-on-background' === $control['css_var'] ) {
			$rgb = hex_to_rgb( $value );
			if ( ! empty( $rgb ) ) {
				$rgb = implode( ',', $rgb );
			}

			$color_vars[] = sprintf( '%s: %s;', esc_html( $control['css_var'] . '-rgb' ), esc_html( $rgb ) );
		}
	}

	$color_vars = implode( "\n\t\t\t", $color_vars );

	return "
		:root {
			{$color_vars}
		}
	";
}

/**
 * Output inline styles with css variables at the top of the head.
 */
function frontend_inline_css() {
	?>
	<style id="material-theme-css-variables">
		<?php echo get_frontend_css(); // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</style>
	<?php
}

/**
 * Convert color hex code to rgb.
 *
 * @param  string|array $hex Hex/RGB of the color.
 * @return mixed
 */
function hex_to_rgb( $hex ) {
	if ( is_array( $hex ) && ! empty( $hex ) ) {
		return $hex;
	}

	$hex = strtolower( ltrim( $hex, '#' ) );
	if ( 3 !== strlen( $hex ) && 6 !== strlen( $hex ) ) {
		return false;
	}

	$values = str_split( $hex, ( 3 === strlen( $hex ) ) ? 1 : 2 );

	return array_map(
		function ( $hex_code ) {
			return hexdec( str_pad( $hex_code, 2, $hex_code ) );
		},
		$values
	);
}


