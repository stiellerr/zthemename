<?php
/**
 * Zthemename Theme Customizer
 *
 * @package zthemename
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zthemename_customize_preview_js() {
	wp_enqueue_script( 'zthemename-customizer', get_template_directory_uri() . '/dist/js/customizer-controls.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'zthemename_customize_preview_js' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zthemename_customize_preview_js() {
	wp_enqueue_script( 'zthemename-customizer', get_template_directory_uri() . '/dist/js/customizer-controls.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'zthemename_customize_preview_js' );

/**
 * Enqueues scripts for customizer controls & settings.
 *
 * @since zthemename 1.0
 *
 * @return void
 */
function zthemename_customize_controls_enqueue_scripts() {
	// Add script for controls.
	wp_enqueue_script( 'zthemename-customize-controls', get_template_directory_uri() . '/dist/js/customize-controls.js', array( 'customize-controls', 'wp-color-picker', 'media-editor', 'underscore' ), _S_VERSION, false );
}

add_action( 'customize_controls_enqueue_scripts', 'zthemename_customize_controls_enqueue_scripts' );

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function zthemename_customize_preview_js() {
	wp_enqueue_script( 'zthemename-customize-preview', get_theme_file_uri( '/js/customize-preview.js' ), array( 'customize-preview' ), '20181214', true );
}
add_action( 'customize_preview_init', 'zthemename_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function zthemename_panels_js() {
	wp_enqueue_script( 'zthemename-customize-controls', get_theme_file_uri( '/js/customize-controls.js' ), array(), '20181214', true );
}
add_action( 'customize_controls_enqueue_scripts', 'zthemename_panels_js' );
