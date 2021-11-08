<?php
/**
 * Custom Colors Class
 */

/**
 * This class is in charge of color customization via the Customizer.
 */
class Zthemename_Custom_Colors {

	/**
	 * Instantiate the object.
	 *
	 * @access public
	 */
	public function __construct() {

		// Enqueue color variables for customizer & frontend.
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_color_variables' ) );

		// Enqueue color variables for editor.
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_custom_color_variables' ) );

	}

	/**
	 * Generate color variables.
	 *
	 * Adjust the color value of the CSS variables depending on the background color theme mod.
	 * Both text and link colors needs to be updated.
	 * The code below needs to be updated, because the colors are no longer theme mods.
	 *
	 * @access public
	 *
	 * @param string|null $context Can be "editor" or null.
	 *
	 * @return string
	 */
	public function generate_custom_color_variables( $context = null ) {

		$theme_css = 'editor' === $context ? ':root .editor-styles-wrapper{' : ':root{';
		
		// header/footer background.
		$header_footer_color = get_theme_mod( 'header_footer_background_color', '#ffffff' );
		$theme_css .= "--global--color-header-footer: {$header_footer_color};";

		// Get the value from the theme-mod.
		$accent_colors = get_theme_mod(
			'accent_colors',
			array(
				'content' => array(
					'accent'       => '#0d6efd',
					'accent-hover' => '#0d6efd',
				),
				'header-footer' => array(
					'accent'       => '#0d6efd',
					'accent-hover' => '#0d6efd',
				),
			)
		);

		foreach( $accent_colors as $area => $values ) {
			foreach( $values as $key => $value ) {
				$theme_css .= "--global--color-{$area}-{$key}: {$value};";
			}
		}

		$theme_css .= '}';

		return $theme_css;
	}

	/**
	 * Customizer & frontend custom color variables.
	 *
	 * @access public
	 *
	 *
	 * @return void
	 */
	public function custom_color_variables() {
		wp_add_inline_style( 'zthemename', $this->generate_custom_color_variables() );
	}

	/**
	 * Editor custom color variables.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function editor_custom_color_variables() {
		
		wp_add_inline_style( 'zthemename-editor', $this->generate_custom_color_variables( 'editor' ) );
		
	}
}
