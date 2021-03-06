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
			// get opening hours.
			$this->opening_hours = get_theme_mod( 'opening_hours' );
			write_log( $this->opening_hours );
		}

		/**
		 * Validates that a date string is in the right format
		 *
		 * @param string $date   date string to be checked / validated.
		 * @param string $format default format is 'H:i' to test for time only in this format '24:00'.
		 *                       but you can pass a new format to test against other formats
		 *                       other formats here https://www.lehelmatyus.com/1003/android-change-date-format-from-utc-to-local-time
		 * 
		 * @return bool
		 */
		protected function zthemename_validate_date( $date, $format = 'H:i' ) {
			// Create the format date.
			$d = DateTime::createFromFormat( $format, $date );

			// Return the comparison.   
			return $d && $d->format( $format ) === $date;
		}

		/**
		 * Sanitizes date time input
		 * https://www.lehelmatyus.com/1416/sanitize-date-time-value-in-wordpress
		 * 
		 * @param string $event_time date string to be checked / validated.
		 * 
		 * @return string null
		 */
		protected function sanitize_event_time( $event_time ) {

			// General sanitization, to get rid of malicious scripts or characters.
			$event_time = sanitize_text_field( $event_time );
			$event_time = filter_var( $event_time, FILTER_SANITIZE_STRING );

			if ( 'Closed' === $event_time || '24 Hours' === $event_time ) {
				return $event_time;
			}

			// Validation to see if it is the right format.
			if ( $this->zthememame_validate_date( $event_time ) ) {
				return $event_time;
			}

			// default value, to return if checks have failed.
			return null;

		}

		/**
		 * Outputs the content for the current Business Hours widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {
		
			// $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__('Contact Form', 'zthemename');
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Business Hours.', 'zthemename' );
		
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			// build array of hours...
			$hours = array(
				'Monday' => array(
					'open'  => $instance['monday-open'],
					'close' => $instance['monday-close'],
				),
				'Tuesday' => array(
					'open'  => $instance['tuesday-open'],
					'close' => $instance['tuesday-close'],
				),
				'Wednesday' => array(
					'open'  => $instance['wednesday-open'],
					'close' => $instance['wednesday-close'],
				),
				'Thursday' => array(
					'open'  => $instance['thursday-open'],
					'close' => $instance['thursday-close'],
				),
				'Friday' => array(
					'open'  => $instance['friday-open'],
					'close' => $instance['friday-close'],
				),
				'Saturday' => array(
					'open'  => $instance['saturday-open'],
					'close' => $instance['saturday-close'],
				),
				'Sunday' => array(
					'open'  => $instance['sunday-open'],
					'close' => $instance['sunday-close'],
				),
			);
			
			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			?>

			<table>
				<tbody>
					<?php
					foreach ( $hours as $day => $val ) {
						if ( $val['open'] ) {
							echo "<tr><td>{$day}</td>";
							echo $val['close'] ? "<td>{$val['open']} - {$val['close']}</td>" : "<td>{$val['open']}</td></tr>";
						}
					}
					?>
				</tbody>
			</table>
			<?php
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
					'title'           => esc_html__( 'Business Hours.', 'zthemename' ),
					'monday-open'     => null,
					'monday-close'    => null,
					'tuesday-open'    => null,
					'tuesday-close'   => null,
					'wednesday-open'  => null,
					'wednesday-close' => null,
					'thursday-open'   => null,
					'thursday-close'  => null,
					'friday-open'     => null,
					'friday-close'    => null,
					'saturday-open'   => null,
					'saturday-close'  => null,
					'sunday-open'     => null,
					'sunday-close'    => null,
				)
			);      

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>">
			</p>

			<div>
				<div>&nbsp;</div>
				<div><?php esc_html_e( 'Open', 'zthemename' ); ?></div>
				<div><?php esc_html_e( 'Close', 'zthemename' ); ?></div>
			</div>

			<div>
				<div><?php esc_html_e( 'Monday:', 'zthemename');  ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'monday-open' ); ?>" name="<?php echo $this->get_field_name( 'monday-open' ); ?>" value="<?php echo esc_attr( $instance['monday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'monday-close' ); ?>" name="<?php echo $this->get_field_name( 'monday-close' ); ?>" value="<?php echo esc_attr( $instance['monday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Tuesday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'tuesday-open' ); ?>" name="<?php echo $this->get_field_name( 'tuesday-open' ); ?>" value="<?php echo esc_attr( $instance['tuesday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'tuesday-close' ); ?>" name="<?php echo $this->get_field_name( 'tuesday-close' ); ?>" value="<?php echo esc_attr( $instance['tuesday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Wednesday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'wednesday-open' ); ?>" name="<?php echo $this->get_field_name( 'wednesday-open' ); ?>" value="<?php echo esc_attr( $instance['wednesday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'wednesday-close' ); ?>" name="<?php echo $this->get_field_name( 'wednesday-close' ); ?>" value="<?php echo esc_attr( $instance['wednesday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Thursday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'thursday-open' ); ?>" name="<?php echo $this->get_field_name( 'thursday-open' ); ?>" value="<?php echo esc_attr( $instance['thursday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'thursday-close' ); ?>" name="<?php echo $this->get_field_name( 'thursday-close' ); ?>" value="<?php echo esc_attr( $instance['thursday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Friday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'friday-open' ); ?>" name="<?php echo $this->get_field_name( 'friday-open' ); ?>" value="<?php echo esc_attr( $instance['friday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'friday-close' ); ?>" name="<?php echo $this->get_field_name( 'friday-close' ); ?>" value="<?php echo esc_attr( $instance['friday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Saturday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'saturday-open' ); ?>" name="<?php echo $this->get_field_name( 'saturday-open' ); ?>" value="<?php echo esc_attr( $instance['saturday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'saturday-close' ); ?>" name="<?php echo $this->get_field_name( 'saturday-close' ); ?>" value="<?php echo esc_attr( $instance['saturday-close'] ); ?>">
			</div>

			<div>
				<div><?php esc_html_e( 'Sunday:', 'zthemename' ); ?></div>
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'sunday-open' ); ?>" name="<?php echo $this->get_field_name( 'sunday-open' ); ?>" value="<?php echo esc_attr( $instance['sunday-open'] ); ?>">
				<input list="hours" size="9" type="text" id="<?php echo $this->get_field_id( 'sunday-close' ); ?>" name="<?php echo $this->get_field_name( 'sunday-close' ); ?>" value="<?php echo esc_attr( $instance['sunday-close'] ); ?>">
			</div>
			<br/>

			<datalist id="hours">
				<option value="Closed">
				<option value="24 Hours">
				<option value="00:00">
				<option value="00:30">
				<option value="01:00">
				<option value="01:30">
				<option value="02:00">
				<option value="02:30">
				<option value="03:00">
				<option value="03:30">
				<option value="04:00">
				<option value="04:30">
				<option value="05:00">
				<option value="05:30">
				<option value="06:00">
				<option value="06:30">
				<option value="07:00">
				<option value="07:30">
				<option value="08:00">
				<option value="08:30">
				<option value="09:00">
				<option value="09:30">
				<option value="10:00">
				<option value="10:30">
				<option value="11:00">
				<option value="11:30">
				<option value="12:00">
				<option value="12:30">
				<option value="13:00">
				<option value="13:30">
				<option value="14:00">
				<option value="14:30">
				<option value="15:00">
				<option value="15:30">
				<option value="16:00">
				<option value="16:30">
				<option value="17:00">
				<option value="17:30">
				<option value="18:00">
				<option value="18:30">
				<option value="19:00">
				<option value="19:30">
				<option value="20:00">
				<option value="20:30">
				<option value="21:00">
				<option value="21:30">
				<option value="22:00">
				<option value="22:30">
				<option value="23:00">
				<option value="23:30">
			</datalist>
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

			$instance = $old_instance;

			$new_instance = wp_parse_args(
				(array) $new_instance,
				array(
					'title'           => null,
					'monday-open'     => null,
					'monday-close'    => null,
					'tuesday-open'    => null,
					'tuesday-close'   => null,
					'wednesday-open'  => null,
					'wednesday-close' => null,
					'thursday-open'   => null,
					'thursday-close'  => null,
					'friday-open'     => null,
					'friday-close'    => null,
					'saturday-open'   => null,
					'saturday-close'  => null,
					'sunday-open'     => null,
					'sunday-close'    => null,
				)
			);

			foreach ( $new_instance as $field => $val ) {
				if ( 'title' == $field ) {
					$instance[ $field ] = sanitize_text_field( $val );
				} else {
					$instance[ $field ] = $this->sanitize_event_time( $val );
				}
			}

			return $instance;
		}
	}
}
