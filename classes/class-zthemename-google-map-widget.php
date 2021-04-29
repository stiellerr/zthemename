<?php
/**
 * Zthemename google map custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Google_Map_Widget' ) ) {

	/**
	 * Custom class used to implement the Google Map widget.
	 */
	class Zthemename_Google_Map_Widget extends WP_Widget {

		/**
		 * Sets up a new google map widget instance.
		 */
		public function __construct() {
			parent::__construct(
				'zthemename_google_map',
				esc_html__( 'Google Map', 'zthemename' ),
				array(
					'description'                 => esc_html__( 'Adds a google static map to your website.', 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
			// google maps co ordinates.			
			$this->geo	   = get_theme_mod( 'geo' );
			$this->map_url = get_theme_mod( 'map_url' );
		}

		/**
		 * Outputs the content for the current Google Map widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {

			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Google Map.', 'zthemename' );
	
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
	
			echo $args['before_widget'];
	
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
	
			if ( $this->geo ) {

				$key = get_option( 'zthemename_options' )['key'];
	
				if ( $key ) {
					$params = array(
						'zoom'   => isset( $instance['zoom'] ) ? $instance['zoom'] : 11,
						'format' => 'jpg',
						'size'   => '208x180',
						'center' => implode( ",", $this->geo ),
						'key'    => $key,
					);
					$params['markers'] = $params['center'];
	
					// build image src url
					$request = add_query_arg(
						$params,
						'https://maps.googleapis.com/maps/api/staticmap'
					);

					$output = '<img class="border" src="' . $request . '" alt="google map" width="208" height="180">';

					if ( $this->map_url ) {
						$output = '<a href="' . $this->map_url . '" target="_blank">' . $output . '</a>';
					}
					echo $output;              
				}
			}
			echo $args['after_widget'];
		}

		/**
		 * Handles updating settings for the google map widget instance.
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;
			
			$new_instance = wp_parse_args(
				(array) $new_instance,
				array(
					'title' => '',
					'zoom'  => 11,
				)
			);

			$instance['title'] = sanitize_text_field( $new_instance['title'] );

			if ( in_array( (int) $new_instance['zoom'], range( 0, 21 ), true ) ) {
				$instance['zoom'] = (int) $new_instance['zoom'];
			} else {
				$instance['zoom'] = 0;
			}

			return $instance;
		}

		/**
		 * Outputs the settings form for the google map widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {

			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title' => esc_html__( 'Google Map', 'zthemename' ),
					'zoom'  => 11,
				)
			);

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'latitude' ); ?>"><?php esc_html_e( 'Latitude:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'latitude' ); ?>" type="text" value="<?php echo $this->geo ? esc_attr( $this->geo['latitude'] ) : ''; ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'longitude' ); ?>"><?php esc_html_e( 'Longitude:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'longitude' ); ?>" type="text" value="<?php echo $this->geo ? esc_attr( $this->geo['longitude'] ) : ''; ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'map_url' ); ?>"><?php esc_html_e( 'Map URL:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'map_url' ); ?>" type="text" value="<?php echo esc_url( $this->map_url ); ?>" readonly>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'zoom' ); ?>"><?php esc_html_e( 'Zoom Level:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'zoom' ); ?>" name="<?php echo $this->get_field_name( 'zoom' ); ?>" type="range" min="0" max="21" step="1" value="<?php echo esc_attr( $instance['zoom'] ); ?>"<?php echo $this->geo ? '' : ' disabled'; ?>>
			</p>			
			<p>
				<?php
					$url = admin_url( 'themes.php?page=zthemename-options' );
					/* translators: %s: URL to create a new menu. */
					printf( __( 'Synchronize google map data <a href="%s">here</a>.' ), esc_attr( $url ) );
				?>
			</p>
			<?php
		}
	}
}

/**
 * Register Custom widget class used to implement the Google Map widget.
 */
function zthemename_register_google_map_widget() {
	register_widget( 'Zthemename_Google_Map_Widget' );
}

add_action( 'widgets_init', 'zthemename_register_google_map_widget' );

