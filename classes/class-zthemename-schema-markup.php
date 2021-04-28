<?php
/**
 * Zthemename schema markup class
 *
 * @package zthemename
 */

//write_log('zzzz');

if ( ! class_exists( 'Zthemename_Schema_Markup' ) ) {

	/**
	 * Custom class used to print schema markup
	 */
	class Zthemename_Schema_Markup {

		/**
		 * Sets up a new contact form widget instance.
		 */
		public function __construct() {

			// print schema markup
			//add_action( 'wp_head', array( &$this, 'schema_markup1' ) );

			// print schema markup
			add_action( 'wp_print_scripts', array( &$this, 'generate_schema_markup' ) );

			//write_log( 'Hello World!' );
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
		 * @since Twenty Twenty-One 1.0
		 *
		 * @param string|null $context Can be "editor" or null.
		 *
		 * @return string
		 */
		public function generate_schema_markup2( $context = null ) {

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
		 * @since Twenty Twenty-One 1.0
		 *
		 * @return void
		 */
		public function generate_schema_markup() {

			$output = array(
				"@context"   => "https://schema.org",
				"@type"      => "LocalBusiness",
				"name"       => get_bloginfo( 'name' ),
				"address"    => array(
					"@type" => "PostalAddress"
				)
			);

			$phone      = get_theme_mod( 'phone' );
			$address    = get_theme_mod( 'address' );
			$priceRange = get_theme_mod( 'priceRange' );
			
			unset( $address['sublocality'] );

			$phone      && $output["telephone"]  = $phone;
			$address    && $output["address"]   += $address;
			$priceRange && $output["priceRange"] = $priceRange;

			// print schema markup.
			printf( "<script type='application/ld+json'>\n%s\n</script>\n", json_encode( $output ) ); 
		}

		/**
		 * Customizer & frontend custom color variables.
		 *
		 * @access public
		 *
		 * @since Twenty Twenty-One 1.0
		 *
		 * @return void
		 */
		public function schema_markup1() {
			//wp_add_inline_style( 'zthemename', $this->generate_schema_markup() );
			echo 'wp_head';
		}

		/**
		 * Customizer & frontend custom color variables.
		 *
		 * @access public
		 *
		 * @since Twenty Twenty-One 1.0
		 *
		 * @return void
		 */
		public function schema_markup2() {
			//wp_add_inline_style( 'zthemename', $this->generate_schema_markup() );
			echo 'wp_print_scripts';
		}
	}
}

// intialize
new Zthemename_Schema_Markup();
