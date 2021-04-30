<?php
/**
 * Zthemename options page custom class
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Options_Page' ) ) {

	/**
	 * Custom class used to implement the Page Excerpt widget.
	 */
	class Zthemename_Options_Page {

		/**
		 * Sets up a new page excerpt widget instance.
		 */
		public function __construct() {
			if ( is_admin() ) {
				// Add the menu screen for inserting license information.
				add_action( 'admin_menu', array( &$this, 'add_menu' ) );
				add_action( 'admin_init', array( &$this, 'admin_init' ) );

				add_action( 'wp_ajax_download_data', array( &$this, 'download_data' ) ); // This is for authenticated users
				add_action( 'wp_ajax_nopriv_download_data', array( &$this, 'download_data' ) ); // This is for unauthenticated users.

				$this->options = get_option( 'zthemename_options' );
			}
		}

		public function download_data() {

			// check ajax source is valid.
			check_admin_referer( 'zthemename-options-options' );

			$args = array_filter(
				get_option( 'zthemename_options' ),
				function( $key ) {
					if ( in_array( $key, array( 'key', 'place_id' ) ) ) {
						return true;
					}
				},
				ARRAY_FILTER_USE_KEY
			);
	
			// build url
			$request = add_query_arg(
				$args,
				'https://maps.googleapis.com/maps/api/place/details/json'
			);

			// send request.
			$response = wp_remote_get( $request );

			//write_log( $response );
	
			if ( is_wp_error( $response ) ) {
				// Bail early
				wp_send_json_error( $response, 500 );
			}
	
			$body = wp_remote_retrieve_body( $response );

			$result = json_decode( $body )->result;

			//write_log( $result->photos );

			// maniuplate place data into our db.
			// things to explore further...
			// testimonials.
			// photos / gallery. media_sideload_image
			//$ttt1 = media_sideload_image( 'https://www.murraybros.co.nz/images/photo/service/stock-cartage.jpg' );
			//$ttt2 = media_sideload_image( 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=403&photoreference=ATtYBwKFzcADJXgaBCxpUP8ixBZSfn7yPKpnqV0Gcrv9MM4eVCErhCG60bcQgQdUj7ShL8pw9vatdX9xPIULbvDydRSueqYifMCfYrWuAGRi3N9itn7h2BVboVXqsH2SweGaQ9yj6wg90bVpI5EHA7LDBPchfppLemvmCt20LsS6jC_f8Azd&key=AIzaSyCKPAa7QAk7mOdAzqD64OHmrBMW3hT8998&name=test.jpg' );
			//write_log( $ttt1 );
			//write_log( $ttt2 );

			// user ratings total.
			isset( $result->formatted_phone_number ) 
				&& set_theme_mod( 'phone', $result->formatted_phone_number );

			if ( isset( $result->geometry->location->lat ) && isset( $result->geometry->location->lng ) ) {
				set_theme_mod(
					'geo',
					array(
						'latitude' => $result->geometry->location->lat,
						'longitude' => $result->geometry->location->lng
					)
				);
			}

			write_log( $result );

			isset( $result->url ) 
				&& set_theme_mod( 'map_url', $result->url );

			if ( isset( $result->rating ) && isset( $result->user_ratings_total ) ) {
				set_theme_mod(
					'rating',
					array(
						"ratingValue" => $result->rating,
						"reviewCount" => $result->user_ratings_total
					)
				);
			}

			isset( $result->name ) 
				&& update_option( 'blogname', $result->name );

			// price range.
			if ( isset( $result->price_level ) ) {
				$priceRange = str_repeat( '$', $result->price_level );
				set_theme_mod( 'priceRange', $priceRange );
			}

			if ( isset( $result->address_components ) ) {
				
				$fields = array(
					'streetAddress' => array( "subpremise", "street_number", "route" ),
					'sublocality' => 'sublocality',
					'addressLocality' => 'locality',
					"addressRegion" => "administrative_area_level_1",
					'postalCode' => "postal_code",
					'addressCountry' => "country"
				);

				$address = array();

				foreach ( $fields as $key => $value ) {
					// convert string to array
					if ( is_string( $value ) ) {
						$value = array( 0 => $value );
					}
					foreach( $value as $field ) {
						foreach( $result->address_components as $element ) {
							if ( in_array( $field, $element->types ) ) {
								if ( isset( $address[ $key ] ) ) {
									//write_log( $field );
									$address[ $key ] .= ( "street_number" === $field ? "/" : " ") . $element->long_name;
								} else {
									$address[ $key ] = $element->long_name;
								}
							}
						}
					}
				}
				set_theme_mod( 'address', $address );
			}

			//write_log( $result );

			// opening hours...
			if ( isset( $result->opening_hours->periods ) ) {

				$hours = array(
					array(
						'dayOfWeek' => array(
							"Monday",
							"Tuesday",
							"Wednesday",
							"Thursday",
							"Friday",
							"Saturday",
							"Sunday"
						),
						"opens"  => "0000",
						"closes" => "0000"
					)
				);

				$DAYS = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );

				foreach ( $result->opening_hours->periods as $period ) {
					foreach( $hours as $index => &$hour ) {
						//				
						if ( $hour['opens'] === $period->open->time && $hour['closes'] === $period->close->time ) {
							if ( 0 === $index ) {
								$period->close->time = '2359';
								break;
							}
							// move day to the new array.
							$hour['dayOfWeek'][] = $DAYS[ $period->open->day ];
							// remove day from original array.
							array_splice(
								$hours[0]['dayOfWeek'],
								array_search( $DAYS[ $period->open->day ], $hours[0]['dayOfWeek'] ),
								1
							);
							continue 2;
						}
					}
					// add anditional element to the array
					$hours[] = array(
						'dayOfWeek' => array(
							$DAYS[ $period->open->day ]
						),
						'opens' => $period->open->time,
						'closes' => $period->close->time
					);

					array_splice(
						$hours[0]['dayOfWeek'],
						array_search( $DAYS[ $period->open->day ], $hours[0]['dayOfWeek'] ),
						1
					);
				}
				// clean the array up here...
				// to do change single element day of week arrays to strings
				foreach( $hours as &$day ) {
					if ( !$day['dayOfWeek'] ) {
						array_shift( $hours );
					}
					// format
					$day['opens']  = DateTime::createFromFormat( 'Hi', $day['opens'] )->format( 'H:i' );
					$day['closes'] = DateTime::createFromFormat( 'Hi', $day['closes'] )->format( 'H:i' );
				}
				set_theme_mod( 'opening_hours', $hours );
			}

			if ( !isset( $result->photos ) ) {

				//write_log( 'dowloading photos...' );
				// download photos into image library.
				$name = get_bloginfo( 'name' );
				$title = sanitize_title( $name ) . ".jpg";
				$api_key = $args[ 'key' ];
				//

				//$i = 0;
				foreach ( $result->photos as $photo ) {

					$args = array(
						'maxwidth' => $photo->width,
						'photoreference' => $photo->photo_reference,
						'key' => $api_key
					);

					// build url
					$file = add_query_arg(
						$args,
						'https://maps.googleapis.com/maps/api/place/photo'
					);

					//write_log( $file );
					//write_log( $i );

					//if ( $i > -1 ) {

						
					
					$file_array         = array();
					$file_array['name'] = $title;
			
					
					// Download file to temp location.
					$file_array['tmp_name'] = download_url( $file );


					//write_log( $file_array['tmp_name'] );
					
					// If error storing temporarily, return the error.
					if ( is_wp_error( $file_array['tmp_name'] ) ) {
						//return $file_array['tmp_name'];
						//write_log('bad');
						continue;
					}
					//write_log('good');

					// Do the validation and storage stuff.
					//$id = media_handle_sideload( $file_array, $post_id, $desc );
					$id = media_handle_sideload( $file_array );

					// If error storing permanently, unlink.
					if ( is_wp_error( $id ) ) {
						@unlink( $file_array['tmp_name'] );
						//return $id;
						continue;
					}

					// Store the original attachment source in meta.
					add_post_meta( $id, '_source_url', $file );
					
					//write_log( $request );

					//$title = sanitize_title( get_bloginfo( $name ) );
					//$url = https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=CnRtAAAATLZNl354RwP_9UKbQ_5Psy40texXePv4oAlgP4qNEkdIrkyse7rPXYGd9D_Uj1rVsQdWT4oRz4QrYAJNpFX7rzqqMlZw2h2E2y5IKMUZ7ouD_SlcHxYq1yL4KbKUv3qtWgTK0A6QbGh87GB3sscrHRIQiG2RrmU_jF4tENr9wGS_YxoUSSDrYjWmrNfeEHSGSc3FyhNLlBU&key=YOUR_API_KEY

					//write_log( $photo );
					//
					//
					// mb_convert_case
					//
					//
					//
					//write_log( $photo->width );
					//write_log( $photo->height );
					//write_log( $photo->photo_reference );
					
					//}
					//$i = $i + 1;
					
				}
			}

			write_log( $result->reviews );

			if ( isset( $result->reviews ) ) {
				foreach( $result->reviews as $review ) {

					$utc_offset = (int) $result->utc_offset * 60;
					$unix_time 	= (int) $review->time + $utc_offset;

					$post_id = wp_insert_post(
						array(
							'post_content' => $review->text,
							'post_title' => $review->author_name,
							'post_type' => 'zthemename_reviews',
							'post_date' => DateTime::createFromFormat( 'U', $unix_time )->format( 'Y-m-d H:i:s' ),
						)
					);

					// download image.
					$file = $review->profile_photo_url;

					$file_array         = array();
					$file_array['name'] = sanitize_title( $review->author_name ) . ".png";
			
					// Download file to temp location.
					$file_array['tmp_name'] = download_url( $file );

					// If error storing temporarily, return the error.
					if ( is_wp_error( $file_array['tmp_name'] ) ) {
						//return $file_array['tmp_name'];
						continue;
					}

					//$desc = 'zzzz';

					// Do the validation and storage stuff.
    				$id = media_handle_sideload( $file_array, $post_id, $review->author_name );

					// If error storing permanently, unlink.
    				if ( is_wp_error( $id ) ) {
        				@unlink( $file_array['tmp_name'] );
        				//return $id;
						continue;
    				}

					set_post_thumbnail( $post_id, $id );
				}
			}

			if ( isset( $result->utc_offset ) ) {
				$utc_hours = (int) $result->utc_offset / 60;

				update_option( 'gmt_offset', $utc_hours );
				update_option( 'timezone_string', '' );
			}

			wp_send_json_success();
		}

		public function add_menu() {

			// add theme page.
			add_theme_page(
				__( 'DIY Marketer', 'zthemename' ),
				__( 'DIY Marketer', 'zthemename' ),
				'manage_options',
				'zthemename-options',
				array( &$this, 'render_page' )
			);
		}
		
		public function admin_init() {

			register_setting(
				'zthemename-options',
				'zthemename_options',
				array(
					'sanitize_callback' => array( &$this, 'sanitize_options' ),
				)
			);

			add_settings_section(
				'zthemename_google',
				__( 'Google Settings', 'zthemename' ),
				array( &$this, 'render_void' ),
				'zthemename-options'
			);

			add_settings_field( 
				'key',
				__( 'API Key', 'zthemename' ),
				array( &$this, 'render_input' ),
				'zthemename-options',
				'zthemename_google',
				array(
					'label_for' => 'zthemename_options[key]',
					'id'        => 'key',
					'caption'	=> sprintf( '<p>Setup you api key <a href="%s" target="_blank">here.</a></p>', esc_url( 'https://developers.google.com/maps/documentation/places/web-service/get-api-key' ) )
				)
			);
	
			add_settings_field( 
				'place_id',
				__( 'Place ID', 'zthemename' ),
				array( &$this, 'render_input' ),
				'zthemename-options',
				'zthemename_google',
				array(
					'label_for' => 'zthemename_options[place_id]',
					'id'        => 'place_id',
					'caption'	=> sprintf( '<p>Find your place id <a href="%s" target="_blank">here.</a></p>', esc_url( 'https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder' ) )
				)
			);

			add_settings_field( 
				'button',
				__( '&nbsp;', 'zthemename' ),
				array( &$this, 'render_button' ),
				'zthemename-options',
				'zthemename_google'
			);

			add_settings_section(
				'zthemename_google_analytics',
				__( 'Google Analytics', 'zthemename' ),
				array( &$this, 'render_void' ),
				'zthemename-options'
			);

			add_settings_field( 
				'js_code',
				__( 'Javascript Code', 'zthemename' ),
				array( &$this, 'render_textarea' ),
				'zthemename-options',
				'zthemename_google_analytics',
				array(
					'label_for' => 'zthemename_options[js_code]',
					'id'        => 'js_code',
				)
			);

			add_settings_section(
				'zthemename_socials',
				__( 'Social Media URL\'s', 'zthemename' ),
				array( &$this, 'render_void' ),
				'zthemename-options'
			);

			add_settings_field( 
				'facebook',
				sprintf( '<i class="fa-lg fa-fw fab fa-facebook"></i>&nbsp;%s', __( 'Facebook', 'zthemename' ) ),
				array( &$this, 'render_url' ),
				'zthemename-options',
				'zthemename_socials',
				array(
					'label_for' => 'zthemename_options[social_media][facebook]',
					'id'        => 'facebook'
				)
			);


			add_settings_field( 
				'instagram',
				sprintf( '<i class="fa-lg fa-fw fab fa-instagram"></i>&nbsp;%s', __( 'Instagram', 'zthemename' ) ),
				array( &$this, 'render_url' ),
				'zthemename-options',
				'zthemename_socials',
				array(
					'label_for' => 'zthemename_options[social_media][instagram]',
					'id'        => 'instagram'
				)
			);

			add_settings_field( 
				'youtube',
				sprintf( '<i class="fa-lg fa-fw fab fa-youtube"></i>&nbsp;%s', __( 'Youtube', 'zthemename' ) ),
				array( &$this, 'render_url' ),
				'zthemename-options',
				'zthemename_socials',
				array(
					'label_for' => 'zthemename_options[social_media][youtube]',
					'id'        => 'youtube'
				)
			);

			add_settings_field( 
				'twitter',
				sprintf( '<i class="fa-lg fa-fw fab fa-twitter"></i>&nbsp;%s', __( 'Twitter', 'zthemename' ) ),
				array( &$this, 'render_url' ),
				'zthemename-options',
				'zthemename_socials',
				array(
					'label_for' => 'zthemename_options[social_media][twitter]',
					'id'        => 'twitter'
				)
			);
		}
		
			// render input
		public function render_void( $args ) {
			return false;
		}

		// render button
		public function render_button( $args ) {
			if ( $this->options && $this->options['place_id'] && $this->options['key'] ) {
				echo '<input id="download_data" type="button" value="Download Data" class="button button-secondary">';
			}
		}

		// render input
		public function render_url( $args ) {
			printf( 
				'<input type="url" id="zthemename_options[social_media][%1$s]" name="zthemename_options[social_media][%1$s]" value="%2$s" class="regular-text">',
				esc_attr( $args['id'] ),
				$this->options ? esc_attr( $this->options['social_media'][$args['id']] ) : ''
			);
		}

		// render input
		public function render_input( $args ) {
			printf( 
				'<input type="text" id="zthemename_options[%1$s]" name="zthemename_options[%1$s]" value="%2$s" class="regular-text">',
				esc_attr( $args['id'] ),
				$this->options ? esc_attr( $this->options[ $args['id'] ] ) : ''
			);
			echo isset( $args['caption'] ) ? $args['caption'] : '';
		}

		// render textarea
		public function render_textarea( $args ) {
			printf( 
				'<textarea id="zthemename_options[%1$s]" name="zthemename_options[%1$s]" class="large-text code" rows="6">%2$s</textarea>',
				esc_attr( $args['id'] ),
				$this->options ? esc_attr( $this->options[ $args['id'] ] ) : ''
			);
		}
		
			// render page
		public function render_page() {

			// check user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			?>
			<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<form action="options.php" method="post">
					<?php
					// output security fields for the registered setting "wporg"
					settings_fields( 'zthemename-options' );
					// output setting sections and their fields
					// (sections are registered for "wporg", each field is registered to a specific section)
					do_settings_sections( 'zthemename-options' );
					// output save settings button
					submit_button( 'Save Settings' );
					?>
				</form>
			</div>
			<?php
		}
		
		public function sanitize_options( $data ) {

			foreach ( $data as $key => $value ) {
				if ( 'social_media' === $key ) {
					foreach( $value as $i => $url ) {
						$data[ $key ][ $i ] = esc_url( $url );
					}
					continue;
				}
				if ( 'js_code' === $key ) {
					$code = strip_tags( $value, '<script>' );
					if ( preg_match( '@<(script)[^>]*?>.*?</\\1>@si', $code ) ) {
						// remove line breaks. this could be done where the script is printed to the page?? .
						$code = preg_replace( '/[\r\n\t ]+/', '', $code );
						$data[ $key ] = trim( $code );
					} else {
						$data[ $key ] = '';
					}
					continue;
				} 
				$data[ $key ] = sanitize_text_field( $value );
			}
			return $data;
		}
	}
}

