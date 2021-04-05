<?php
/**
 * Zthemename Theme Customizer
 *
 * @package zthemename
 */

/**
 * Add custom controls and settings for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function zthemename_customize_register( $wp_customize ) {

	$wp_customize->add_setting(
		'nav_theme',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => 'navbar-light',
			'sanitize_callback' => 'sanitize_nav_theme',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'nav_color',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'accent_color',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => '#0d6efd',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'nav_btn_type',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => '',
			// 'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

}

add_action( 'customize_register', 'zthemename_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zthemename_customize_preview() {
	wp_enqueue_script( 'zthemename-customize-preview', get_template_directory_uri() . '/dist/js/customize-preview.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'zthemename_customize_preview' );

/**
 * Enqueue scripts for the customizer.
 */
function zthemename_customize_controls_enqueue_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'zthemename-customize-constrols', get_template_directory_uri() . '/dist/js/customize-controls.js', array( 'customize-controls', 'wp-color-picker' ), _S_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'zthemename_customize_controls_enqueue_scripts' );

/**
 * Sanitize nav theme.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The input from the setting or the default setting.
 */
function sanitize_nav_theme( $input, $setting ) {
	$input   = sanitize_key( $input );
	$options = array( 'navbar-light', 'navbar-dark' );
	return ( in_array( $input, $options, true ) ? $input : $setting->default );
}
