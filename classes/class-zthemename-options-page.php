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

			isset( $result->geometry->location->lat ) 
				&& set_theme_mod( 'latitude', $result->geometry->location->lat );

			isset( $result->geometry->location->lng ) 
				&& set_theme_mod( 'longitude', $result->geometry->location->lng );

			isset( $result->url ) 
				&& set_theme_mod( 'map_url', $result->url );

			isset( $result->rating ) 
				&& set_theme_mod( 'rating', $result->rating );

			isset( $result->name ) 
				&& update_option( 'blogname', $result->name );

			// price range.
			if ( isset( $result->price_level ) ) {
				$priceRange = str_repeat( '$', $result->price_level );
				set_theme_mod( 'priceRange', $priceRange );
			}

			/*
			if ( isset( $result->formatted_address ) ) {
				// could also replace out the country here as well, by grabbing it from the address data.
				$address = preg_replace( '@, @si', "\n", $result->formatted_address );
				set_theme_mod( 'address', $address );
			}
			*/

			// address new.
			if ( isset( $result->address_components ) ) {

				//write_log( $result );
				
				$fields = array(
					'streetAddress' => array( "subpremise", "street_number", "route" ),
					'sublocality' => 'sublocality',
					'addressLocality' => 'locality',
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
			
			//write_log( 'result' );
			//write_log( $result );
			
			if ( isset( $result->opening_hours->weekday_text ) ) {
				// extract hours, modify format & pump into the db.
				foreach ( $result->opening_hours->weekday_text as $value ) {
					if ( preg_match( '/\A[A-Z][a-z]+/', $value, $matches ) ) {
						$day = $matches[0];
						//if ( preg_match_all( '/\d:\d{2} [AP]M/', $value, $matches ) ) {
						if ( preg_match_all( '/\d:\d{2}/', $value, $matches ) ) {
							// modify time format.
							foreach( $matches[0] as &$match ) {
								$match = DateTime::createFromFormat( 'h:i', $match )->format( 'H:i' );
							}
							$hours[ $day ] = $matches[0];
						} elseif ( preg_match( '/(?:Closed|Open 24 hours)/', $value, $matches ) ) {
							$hours[ $day ] = $matches;
						}
					}
				}

				write_log( $hours );

				isset( $hours ) &&
					set_theme_mod( 'opening_hours', $hours );
			} else {
				set_theme_mod( 'opening_hours', array() );
			}

			// opening hours...
			if ( isset( $result->opening_hours->periods ) ) {

				//$days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
				//$hours = array();

				foreach ( $result->opening_hours->periods as $period ) {
					
					//$period->open->day

					$hours = array(
						'dayOfWeek' => array(
							"Sunday",
							"Monday",
							"Tuesday",
							"Wednesday",
							"Thursday",
							"Friday",
							"Saturday"
						),
						"opens"  => "08:00",
						"closes" => "00:00"
					);

					//
					if ( $hours['opens'] !== $period->open->time || $hours['closes'] !== $period->close->time ) {
						
						//$period->open->time
					}

					//$day $period->open->day

					//write_log( json_encode( $hours2 ) );
					
					/*
					$temp = array(
						'opens'  => DateTime::createFromFormat( 'Hi', $period->open->time )->format( 'H:i' ),
						'closes' => DateTime::createFromFormat( 'Hi', $period->close->time )->format( 'H:i' )
					)
					
					$hours[ $days[ $period->open->day ] ] = array(
						'opens'  => DateTime::createFromFormat( 'Hi', $period->open->time )->format( 'H:i' ),
						'closes' => DateTime::createFromFormat( 'Hi', $period->close->time )->format( 'H:i' )
					);
					*/
				}

				/*
				$i = array(
					'dayOfWeek' => null,
					'opens'		=> '00:00',
					'closes'	=> '00:00'
				);

				write_log( $i );
				*/


				// extract hours, modify format & pump into the db.
				//$hours[] = array(

				//)
				//write_log( $result->opening_hours->periods );
				//write_log( $result->opening_hours->weekday_text );
				
				//$days => array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );



				/*
				$temp = array(
					'dayOfWeek' => array(),
					'opens' => '',
					'closes' => ''
				);
				
				
				foreach ( $result->opening_hours->periods as $period ) {
					$temp
					//write_log( $period );
				}
				*/
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

			wp_send_json_success();

			//wp_send_json_success( json_decode( $body ) );
			// wp_send_json_success( $response );
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
		}
		
			// render input
		public function render_void( $args ) {
			return false;
		}

		// render button
		public function render_button( $args ) {
			if ( $this->options['place_id'] && $this->options['key'] ) {
				echo '<input id="download_data" type="button" value="Download Data" class="button button-secondary">';
			}
		}

		// render input
		public function render_input( $args ) {
			printf( 
				'<input type="text" id="zthemename_options[%1$s]" name="zthemename_options[%1$s]" value="%2$s" class="regular-text">',
				esc_attr( $args['id'] ),
				esc_attr( $this->options[ $args['id'] ] )
			);
			echo isset( $args['caption'] ) ? $args['caption'] : '';
		}

		// render textarea
		public function render_textarea( $args ) {
			printf( 
				'<textarea id="zthemename_options[%1$s]" name="zthemename_options[%1$s]" class="large-text code" rows="6">%2$s</textarea>',
				esc_attr( $args['id'] ),
				esc_attr( $this->options[ $args['id'] ] )
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
				if ( 'js_code' === $key ) {
					$value = strip_tags( $value, '<script>' );
					if ( preg_match( '@<(script)[^>]*?>.*?</\\1>@si', $value ) ) {
						// remove line breaks. this could be done where the script is printed to the page?? .
						$value = preg_replace( '/[\r\n\t ]+/', '', $value );
						$data[ $key ] = trim( $value );
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

