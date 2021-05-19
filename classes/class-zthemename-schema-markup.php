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

			
			//$start = microtime( true );
			/*

			$output = array(
				"@context"   => "https://schema.org",
				"@type"      => "LocalBusiness",
				"name"       => get_bloginfo( 'name' ),
				"address"    => array(
					"@type" => "PostalAddress"
				)
			);

			//
			$phone      = get_theme_mod( 'phone' );
			$address    = get_theme_mod( 'address' );
			$priceRange = get_theme_mod( 'priceRange' );
			$hours      = get_theme_mod( 'opening_hours' );
			$geo        = get_theme_mod( 'geo' );
			$rating     = get_theme_mod( 'rating' );
			
			$options    = get_option( 'zthemename_options' );
			$socials    = $options ? $options['social_media'] : false;

			$socials && $socials = array_filter( array_values( $socials ) );
			$socials && $output["sameAs"]  = $socials;
			
			unset( $address['sublocality'] );

			$phone      && $output["telephone"]  = $phone;
			$address    && $output["address"]   += $address;
			$priceRange && $output["priceRange"] = $priceRange;

			if ( $hours ) {
				foreach( $hours as &$period ) {
					$period = array( '@type' => "OpeningHoursSpecification" ) + $period;
					//$period["@type"] = "OpeningHoursSpecification";
				}
				$output["openingHoursSpecification"] = $hours;
			}
			*/
			/*
			if ( $geo ) {
				$geo["@type"]  = "GeoCoordinates";
				$output["geo"] = $geo;
			}
			*/

			/*
			$reviews = get_posts(
				array(
					'post_type' => 'zthemename_reviews'
				)
			);

			if ( $reviews ) {
				foreach( $reviews as $review ) {
					write_log( $review );
					//$period = array( '@type' => "OpeningHoursSpecification" ) + $period;
					//$period["@type"] = "OpeningHoursSpecification";
				}
				//$output["openingHoursSpecification"] = $hours;
			}
			*/

			//write_log( $reviews );
			/*
			$geo    && $output["geo"] = array( "@type" => "GeoCoordinates" ) + $geo;
			$rating && $output["aggregateRating"] = array( "@type" => "AggregateRating" ) + $rating;
			*/
			//if ( $rating ) {
				//$output["aggregateRating"] = array( "@type" => "AggregateRating" ) + $rating;

				//$rating["@type"]  = "AggregateRating";
				//$output["aggregateRating"] = $rating;
			//}
			$schema = get_theme_mod( 'schema' );

			unset( $schema['address']['sublocality'] );
			unset( $schema['phone'] );

			$sidebars = get_theme_mod( 'socials' );

			if ( $sidebars ) {

				$widgets = array();
	
				foreach ( $sidebars as $key => $sidebar ) {
					$widgets = array_merge( $widgets, $sidebar );
				}
				
				$socials = array_values( array_unique( $widgets ) );

				$socials && $schema['sameAs'] = $socials;
			}

			$menu_location = get_nav_menu_locations();

			write_log('menu_location');
			write_log($menu_location);

			$menu_id = isset( $menu_location['menu-1'] ) ? $menu_location['menu-1'] : false;

			write_log('menu_id');
			write_log($menu_id);

			if ( $menu_id ) {
				$menu_items = wp_get_nav_menu_items( $menu_id );

				if ( $menu_items ) {

					$url = home_url( '/' );
					
					$template = array(
						"@context" => "https://schema.org",
						"@type"    => "BreadcrumbList",
						"itemListElement" => array(
							array(
								"@type" => "ListItem",
								"position" => 1,
								"name" => "Home",
								"item" =>  $url
							)
						)
					);

					foreach( $menu_items as $menu_item ) {
						// bail early
						if ( $url === $menu_item->url || strpos( $menu_item->url, $url ) === false ) {
							continue;
						}
						
						$breadcrumb = $template;
						
						$ListElement = array(
							"@type" => "ListItem",
							"position" => 2,
							"name" => $menu_item->title,
							"item" => $menu_item->url
						);
						$breadcrumb['itemListElement'][] = $ListElement;

						$breadcrumbs[] = $breadcrumb;
						
						//write_log('breadcrumb');
						//write_log($breadcrumb);
					}
				}
				
				write_log('breadcrumbs');
				write_log(json_encode( $breadcrumbs ));
				$schema[] = $breadcrumbs;

			}

			//get_nav_menu_locations()['menu-1']

			// print schema markup.
			$schema && printf( "<script type='application/ld+json'>\n%s\n</script>\n", json_encode( $schema ) );

			//write_log( 'elapsed time in seconds' );
			//$elapsed = microtime( true ) - $start;
			//write_log( $elapsed );
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
