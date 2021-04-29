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
			// get opening hours from db.
			$this->opening_hours = $this->generate_hours();
		}

		public function generate_hours() {

			$hours = get_theme_mod( 'opening_hours' );

			if ( !$hours ) {
				return false;
			}
			
			$DAYS = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
			
			foreach( $DAYS as $day ) {
				foreach( $hours as $period ) {
					if ( in_array( $day, $period['dayOfWeek'] ) ) {
						if ( '00:00' === $period['opens'] ) {
							if ( '00:00' === $period['closes'] ) {
								$return[$day][0] = 'Closed';
							}
							if ( '23:59' === $period['closes'] ) {
								$return[$day][0] = 'Open 24 hours';
							}
							continue;
						}
						$return[$day][0] = $period['opens'];
						$return[$day][1] = $period['closes'];
					}
				}				
			}

			return $return;
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

			if ( $this->opening_hours ) {
				$output = '<table><tbody>';
				foreach ( $this->opening_hours as $key => $value ) {
					$output .= '<tr><td>' . $key . '</td>';
					if ( 1 < count( $value ) ) {
						$output .= '<td><time>' . $value[0] . '</time>' . wptexturize(' - ') . '<time>' . $value[1] . '</time></td>';
					} else {
						$output .= '<td><time>' . $value[0] . '</time></td>';
					}
					$output .= '</tr>';
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
			<div>
				<div>&nbsp;</div>
				<div><?php esc_html_e( 'Open', 'zthemename' ); ?></div>
				<div><?php esc_html_e( 'Close', 'zthemename' ); ?></div>
			</div>

			<div>
				<div><?php esc_html_e( 'Monday:', 'zthemename');  ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Monday'][0] ) ? $this->opening_hours['Monday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Monday'][1] ) ? $this->opening_hours['Monday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Tuesday:', 'zthemename' ); ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Tuesday'][0] ) ? $this->opening_hours['Tuesday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Tuesday'][1] ) ? $this->opening_hours['Tuesday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Wednesday:', 'zthemename' ); ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Wednesday'][0] ) ? $this->opening_hours['Wednesday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Wednesday'][1] ) ? $this->opening_hours['Wednesday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Thursday:', 'zthemename' ); ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Thursday'][0] ) ? $this->opening_hours['Thursday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Thursday'][1] ) ? $this->opening_hours['Thursday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Friday:', 'zthemename' ); ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Friday'][0] ) ? $this->opening_hours['Friday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Friday'][1] ) ? $this->opening_hours['Friday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Saturday:', 'zthemename');  ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Saturday'][0] ) ? $this->opening_hours['Saturday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Saturday'][1] ) ? $this->opening_hours['Saturday'][1] : ''; ?>" readonly>
			</div>

			<div>
				<div><?php esc_html_e( 'Sunday:', 'zthemename');  ?></div>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Sunday'][0] ) ? $this->opening_hours['Sunday'][0] : ''; ?>" readonly>
				<input type="text" size="9" value="<?php echo isset( $this->opening_hours['Sunday'][1] ) ? $this->opening_hours['Sunday'][1] : ''; ?>" readonly>
			</div>

			
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
