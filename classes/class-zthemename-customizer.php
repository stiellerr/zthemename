<?php
/**
 * Zthemename Theme Customizer
 *
 * @link https://https://developer.wordpress.org/themes/customize-api/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Customizer' ) ) {

	/**
	 * Custom class used to add custom controls and settings for the Theme Customizer.
	 */
	class Zthemename_Customizer {

		/**
		 * Register customizer options.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public static function register( $wp_customize ) {
			
			/* Display full content or excerpts on the blog and archives --------- */
			$wp_customize->add_setting(
				'nav_theme',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 'navbar-light',
					'sanitize_callback' => array( __CLASS__, 'sanitize_nav_theme' ),
					'transport'         => 'postMessage',
				)
			);
		
			$wp_customize->add_setting(
				'header_footer_background_color',
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
				'header_footer_button_outline',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => false,
					'sanitize_callback' => 'is_bool',
					'transport'         => 'postMessage',
				)
			);
		}

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
	}

	// Setup the Theme Customizer settings and controls.
	add_action( 'customize_register', array( 'Zthemename_Customizer', 'register' ) );

}

