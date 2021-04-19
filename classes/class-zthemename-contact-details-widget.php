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

			// map theme mods.
			$this->company = get_bloginfo( 'name' );
			$this->email   = get_bloginfo( 'admin_email' );
			$this->web     = get_bloginfo( 'url' );
			
			$this->phone   = get_theme_mod( 'phone' );
			$this->address = get_theme_mod( 'address' );
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

			// bail if no links found.
			if ( $this->company || $this->address || $this->phone || $this->email || $this->web ) {
				
				$output = '<table><tbody>';

				$output .= $this->company ? 
					sprintf(
						'<tr><td><i class="fas fa-user fa-fw" data-content="f007"></i></td><td>%s</td></tr>',
						esc_attr( $this->company ) ) : '';
				
				$output .= $this->address ? 
					sprintf(
						'<tr><td><i class="fas fa-map-marker-alt fa-fw" data-content="f3c5"></i></td><td>%s</td></tr>',
						nl2br( $this->address ) ) : '';

				$output .= $this->phone ? 
					sprintf(
						'<tr><td><i class="fas fa-phone fa-fw" data-content="f095"></i></td><td><a href="tel:%1$s">%1$s</a></td></tr>',
						esc_attr( $this->phone ) ) : '';

				$output .= $this->email ? 
					sprintf(
						'<tr><td><i class="fas fa-envelope fa-fw" data-content="f0e0"></i></td><td><a href="mailto:%1$s">%1$s</a></td></tr>',
						esc_attr( $this->email ) ) : '';

				$output .= $this->web ? 
					sprintf(
						'<tr><td><i class="fas fa-globe fa-fw" data-content="f0ac"></i></td><td><a href="%1$s">%1$s</a></td></tr>',
						esc_attr( $this->web ) ) : '';

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
				<label for="<?php echo $this->get_field_id( 'company' ); ?>"><?php esc_html_e( 'Company Name:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'company' ); ?>" type="text" value="<?php echo esc_attr( $this->company ); ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php esc_html_e( 'Address:', 'zthemename' ); ?></label>
				<textarea class="widefat" rows="4" id="<?php echo $this->get_field_id( 'address' ); ?>"readonly><?php echo esc_attr( $this->address ); ?></textarea>
				<br/>
				<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'Phone:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $this->phone ); ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php esc_html_e( 'Email:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" type="text" value="<?php echo esc_attr( $this->email ); ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'web' ); ?>"><?php esc_html_e( 'Web:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'web' ); ?>" type="text" value="<?php echo esc_url( $this->web ); ?>" readonly>
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
