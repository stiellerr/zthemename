<?php
/**
 * Zthemename Theme Images
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Images' ) ) {

	/**
	 * Custom class used to filter and optimize theme images
	 */
	class Zthemename_Images {

        /**
         * Instantiate the object.
         *
         * @access public
         *
         * @since zthemename 1.0
         */
        public function __construct() {

            write_log( 'Zthemename_Images' );

            // Enqueue color variables for customizer & frontend.
            //add_action( 'wp_enqueue_scripts', array( $this, 'custom_color_variables' ) );

            // Enqueue color variables for editor.
            //add_action( 'enqueue_block_editor_assets', array( $this, 'editor_custom_color_variables' ) );

        }
	}

    new Zthemename_Images();
}

