<?php
/**
 * Zthemename business hours custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'zthemename_Business_Hours_Widget' ) ) {
	
	/**
	 * Class used to implement the Business Hours widget.
	 */
	class Zthemename_Business_Hours_Widget extends WP_Widget {

		/**
		 * Sets up a new business hours instance.
		 */
		public function __construct() {

			parent::__construct(
				'zthemename_business_hours',
				esc_html__( 'Business Hours', 'zthemename' ),
				array(
					'description'                 => esc_html__( "Displays the business' hours", 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Outputs the content for the current Business Hours widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {

			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Business Hours.', 'zthemename' );
		
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			
			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$schema = get_theme_mod( 'schema' );

			$hours = isset( $schema['openingHoursSpecification'] ) ? $schema['openingHoursSpecification'] : false;

			if ( $hours ) {
				
				$DAYS = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );

				$output = '<table><tbody>';
			
				foreach( $DAYS as $day ) {
					$output .= '<tr><td>' . $day . '</td>';
					foreach( $hours as $period ) {
						if ( in_array( $day, $period['dayOfWeek'] ) ) {
							if ( '00:00' === $period['opens'] ) {
								if ( '00:00' === $period['closes'] ) {
									$output .= '<td>Closed</td></tr>';
									continue 2;
								}
								if ( '23:59' === $period['closes'] ) {
									$output .= '<td>Open 24 hours</td></tr>';
									continue 2;
								}
							}
							$output .= '<td><time>' . $period['opens'] . '</time>' . wptexturize(' - ') . '<time>' . $period['closes'] . '</time></td></tr>';
							continue 2;
						}
					}				
				}

				$output .= '</tbody></table>';
				echo $output;
			}

			echo $args['after_widget'];
		}

		/**
		 * Outputs the settings form for the Business Hours widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
		
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'  => esc_html__( 'Business Hours.', 'zthemename' ),
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
					printf( __( 'Synchronize business hours <a href="%s">here</a>.' ), esc_attr( $url ) );
				?>
			</p>
			<?php
		}

		/**
		 * Handles updating settings for the business hours widget instance.
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
