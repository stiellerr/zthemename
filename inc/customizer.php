<?php
/**
 * Zthemename Theme Customizer
 *
 * @package zthemename
 */

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
	wp_enqueue_script( 'zthemename-customize-constrols', get_template_directory_uri() . '/dist/js/customize-controls.js', array( 'customize-controls', 'wp-color-picker' ), _S_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'zthemename_customize_controls_enqueue_scripts' );
