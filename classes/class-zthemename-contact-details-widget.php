<?php
/**
 * Zthemename contact details custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'zthemename_Contact_Details_Widget' ) ) {
	
	/**
	 * Class used to implement the Contact Details widget.
	 */
	class Zthemename_Contact_Details_Widget extends WP_Widget {

		/**
		 * Sets up a new contact details widget instance.
		 */
		public function __construct() {

			parent::__construct(
				'zthemename_contact_details',
				esc_html__( 'Contact Details', 'zthemename' ),
				array(
					'description'                 => esc_html__( "Displays the business' contact details", 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Outputs the content for the current Contact Details widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {
		
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Details.', 'zthemename' );
		
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// map theme mods.
			$name  = get_bloginfo( 'name' );
			$email = get_bloginfo( 'admin_email' );
			$url   = get_bloginfo( 'url' );

			$schema = get_theme_mod( 'schema' );

			$phone               = isset( $schema['phone'] ) ? $schema['phone'] : false;
			$international_phone = isset( $schema['telephone'] ) ? $schema['telephone'] : false;
			$address             = isset( $schema['address'] ) ? $schema['address'] : false;

			// street address.
			$temp = isset( $address['streetAddress'] ) ? $address['streetAddress'] : '';
			// sublocality
			$temp .= $temp && ( isset( $address['sublocality'] ) ) ?  "\n" . $address['sublocality'] : '';
			// locality + post code.
			$temp .= $temp && ( isset( $address['addressLocality'] ) || isset( $address['postalCode'] ) ) ?  "\n" : '';
			
			if ( isset( $address['addressLocality'] ) ) {
				if ( isset( $address['postalCode'] ) ) {
					$temp .= "{$address['addressLocality']} {$address['postalCode']}";
				} else {
					$temp .= $address['addressLocality'];
				}				
			} elseif( isset( $address['postalCode'] ) ) {
				$temp .= $address['postalCode'];
			}

			// bail if no address info found.
			if ( $name || $temp || $phone || $email || $url ) {
								
				$output = '<table><tbody>';

				$output .= $name ? 
					sprintf(
						'<tr><td><i class="fas fa-user fa-fw" data-content="f007"></i></td><td>%s</td></tr>',
						esc_attr( $name ) ) : '';
				
				$output .= $temp ? 
					sprintf(
						'<tr><td><i class="fas fa-map-marker-alt fa-fw" data-content="f3c5"></i></td><td>%s</td></tr>',
						nl2br( $temp ) ) : '';

				$output .= $phone ? 
					sprintf(
						'<tr><td><i class="fas fa-phone-alt fa-fw" data-content="f879"></i></td><td><a href="tel:%1$s">%2$s</a></td></tr>',
						esc_attr( $international_phone ),
						esc_attr( $phone ) ) : '';

				$output .= $email ? 
					sprintf(
						'<tr><td><i class="fas fa-envelope fa-fw" data-content="f0e0"></i></td><td><a href="mailto:%1$s">%1$s</a></td></tr>',
						esc_attr( $email ) ) : '';

				$output .= $url ? 
					sprintf(
						'<tr><td><i class="fas fa-globe fa-fw" data-content="f0ac"></i></td><td><a href="%1$s">%1$s</a></td></tr>',
						esc_attr( $url ) ) : '';

				$output .= '</tbody></table>';

				echo $output;
			}   

			echo $args['after_widget'];
		}

		/**
		 * Outputs the settings form for the Contact Details widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
						
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title' => esc_html__( 'Contact Details.', 'zthemename' ),
				)
			);      

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>
			<p>
				<?php
					$url = admin_url( 'themes.php?page=zthemename-options' );
					/* translators: %s: URL to create a new menu. */
					printf( __( 'Synchronize business details <a href="%s">here</a>.' ), esc_attr( $url ) );
				?>
			</p>
			<?php
		}

		/**
		 * Handles updating settings for the contact details widget instance.
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance          = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
	
			return $instance;
		}
	}
}
