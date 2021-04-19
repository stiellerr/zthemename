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

				add_action( 'wp_ajax_sync_data', array( &$this, 'sync_data' ) ); // This is for authenticated users
				add_action( 'wp_ajax_nopriv_sync_data', array( &$this, 'sync_data' ) ); // This is for unauthenticated users.

				$this->options = get_option( 'zthemename_options' );
			}
		}

		public function sync_data() {

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

			write_log( $response );
	
			if ( is_wp_error( $response ) ) {
				// Bail early
				wp_send_json_error( $response, 500 );
			}
	
			$body = wp_remote_retrieve_body( $response );

			$result = json_decode( $body )->result;

			write_log( $result );

			set_theme_mod( 'phone', $result->formatted_phone_number );
			set_theme_mod( 'latitude', $result->geometry->location->lat );
			set_theme_mod( 'longitude', $result->geometry->location->lng );
			set_theme_mod( 'map_url', $result->url );
			set_theme_mod( 'rating', $result->rating );
			$address = preg_replace( '@, @si', "\n", $result->formatted_address );
			set_theme_mod( 'address', $address );
			update_option( 'blogname', $result->name );
			//update_option( 'blogname', 'Hello World.' );
			//update_option( 'admin_email', 'zzz5@gmail.com' );
			//hours.
			//$result->opening_hours->weekday_text

			//var hours = [
				//{ day: "sunday" },
				//{ day: "monday" },
				//{ day: "tuesday" },
				//{ day: "wednesday" },
				//{ day: "thursday" },
				//{ day: "friday" },
				//{ day: "saturday" }
			//];

			// extract hours, modify format & pump into the db.
			foreach ( $result->opening_hours->weekday_text as $value ) {
				if ( preg_match( '/\A[A-Z][a-z]+/', $value, $matches ) ) {
					$day = $matches[0];
					if ( preg_match_all( '/\d:\d{2} [AP]M/', $value, $matches ) ) {
						// modify time format.
						foreach( $matches[0] as &$match ) {
							$match = DateTime::createFromFormat( 'h:i A', $match )->format( 'H:i' );
						}
						$hours[ $day ] = $matches[0];
					} elseif ( preg_match( '/(?:Closed|Open 24 hours)/', $value, $matches ) ) {
						$hours[ $day ] = $matches;
					}
				}
			}

			write_log( $hours );

			set_theme_mod( 'opening_hours', $hours );
			
			//things to explore further
			//testimonials.
			//photos/gallery.
			//user ratings total.


				
			
			// set_theme_mod( 'zthemename_phone', $result->formatted_phone_number );

			// write_log( $result->formatted_phone_number );
			
			// return data back to js
			wp_send_json_success( json_decode( $body ) );
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
					// 'type' => 'object',
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
				echo '<input id="sync_places" type="button" value="Synchronise Data" class="button button-secondary">';
			}
		}

		// render input
		public function render_input( $args ) {
			printf( 
				'<input type="text" id="zthemename_options[%1$s]" name="zthemename_options[%1$s]" value="%2$s" class="regular-text">',
				esc_attr( $args['id'] ),
				esc_attr( $this->options[ $args['id'] ] )
			);
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

