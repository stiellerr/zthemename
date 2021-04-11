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

		public function print_hours( $open = '', $close = '' ) {

			if ( ! $open || ! $close || 'Closed' === $open || 'Closed' === $close || $open === $close ) {
				return 'Closed';
			}

			if ( 'Open 24Hrs' === $open || 'Open 24Hrs' === $close ) {
				return 'Open 24Hrs';
			}

			return "<time>${open}</time> - <time>${close}</time>";
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

			//$skip = array( '', 'Closed', 'Open' );

			$monday_open  = isset( $instance['monday-open'] ) ? $instance['monday-open'] : '';
			$monday_close  = isset( $instance['monday-close'] ) ? $instance['monday-close'] : '';
			$tuesday_open  = isset( $instance['tuesday-open'] ) ? $instance['tuesday-open'] : '';
			$tuesday_close  = isset( $instance['tuesday-close'] ) ? $instance['tuesday-close'] : '';
			$wednesday_open  = isset( $instance['wednesday-open'] ) ? $instance['wednesday-open'] : '';
			$wednesday_close  = isset( $instance['wednesday-close'] ) ? $instance['wednesday-close'] : '';

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}


			

			// bail if no links found.
			//if ( ! $address ) {
			    //echo $args['after_widget'];
			    //return;
			//}	

			echo '<table style="width: 100%"><tbody>';

			echo '<tr><td>Monday</td><td>' . $this->print_hours( $monday_open, $monday_close ) . '</td></tr>';
			echo '<tr><td>Tuesday</td><td>' . $this->print_hours( $tuesday_open, $tuesday_close ) . '</td></tr>';
			echo '<tr><td>Wednesday</td><td>' . $this->print_hours( $wednesday_open, $wednesday_close ) . '</td></tr>';

			//$address && printf( '<tr><td><i class="fas fa-map-marker-alt fa-fw" data-content="f3c5"></i></td><td>%s</td></tr>', nl2br( $address ) );

			echo '</tbody></table>';
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
					'monday-open'     => '',
					'monday-close'    => '',
					'tuesday-open'    => '',
					'tuesday-close'   => '',
					'wednesday-open'  => '',
					'wednesday-close' => '',
					'thursday-open'   => '',
					'thursday-close'  => '',
					'friday-open'     => '',
					'friday-close'    => '',
					'saturday-open'   => '',
					'saturday-close'  => '',
					'sunday-open'     => '',
					'sunday-close'    => '',
				)
			);		

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>">
			</p>
			<table style="width:100%">
				<tbody>
					<tr>
						<th>&nbsp;</th>
						<th><?php esc_html_e( 'Open:', 'zthemename' ); ?></th>
						<th><?php esc_html_e( 'Close:', 'zthemename' ); ?></th>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Monday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" class="zzz_test" id="<?php echo $this->get_field_id( 'monday-open' ); ?>" name="<?php echo $this->get_field_name( 'monday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'monday-open' ] ); ?>">
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'monday-close' ); ?>" name="<?php echo $this->get_field_name( 'monday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'monday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Tuesday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'tuesday-open' ); ?>" name="<?php echo $this->get_field_name( 'tuesday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'tuesday-open' ] ); ?>">
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'tuesday-close' ); ?>" name="<?php echo $this->get_field_name( 'tuesday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'tuesday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Wednesday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'wednesday-open' ); ?>" name="<?php echo $this->get_field_name( 'wednesday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'wednesday-open' ] ); ?>">
						</td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'wednesday-close' ); ?>" name="<?php echo $this->get_field_name( 'wednesday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'wednesday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Thursday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'thursday-open' ); ?>" name="<?php echo $this->get_field_name( 'thursday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'thursday-open' ] ); ?>">
						</td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'thursday-close' ); ?>" name="<?php echo $this->get_field_name( 'thursday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'thursday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Friday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'friday-open' ); ?>" name="<?php echo $this->get_field_name( 'friday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'friday-open' ] ); ?>">
						</td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'friday-close' ); ?>" name="<?php echo $this->get_field_name( 'friday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'friday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Saturday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'saturday-open' ); ?>" name="<?php echo $this->get_field_name( 'saturday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'saturday-open' ] ); ?>">
						</td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'saturday-close' ); ?>" name="<?php echo $this->get_field_name( 'saturday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'saturday-close' ] ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Sunday:', 'zthemename' ); ?></td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'sunday-open' ); ?>" name="<?php echo $this->get_field_name( 'sunday-open' ); ?>" value="<?php echo esc_attr( $instance[ 'sunday-open' ] ); ?>">
						</td>
						<td>
							<input list="hours" type="text" id="<?php echo $this->get_field_id( 'sunday-close' ); ?>" name="<?php echo $this->get_field_name( 'sunday-close' ); ?>" value="<?php echo esc_attr( $instance[ 'sunday-close' ] ); ?>">
						</td>
					</tr>
				</tbody>
			</table>
			<br/>

			<datalist id="hours">
				<option value="Closed">
				<option value="24 hours">
				<option value="12:00 AM">
				<option value="12:30 AM">
				<option value="01:00 AM">
				<option value="01:30 AM">
				<option value="02:00 AM">
				<option value="02:30 AM">
				<option value="03:00 AM">
				<option value="03:30 AM">
				<option value="04:00 AM">
				<option value="04:30 AM">
				<option value="05:00 AM">
				<option value="05:30 AM">
				<option value="06:00 AM">
				<option value="06:30 AM">
				<option value="07:00 AM">
				<option value="07:30 AM">
				<option value="08:00 AM">
				<option value="08:30 AM">
				<option value="09:00 AM">
				<option value="09:30 AM">
				<option value="10:00 AM">
				<option value="10:30 AM">
				<option value="11:00 AM">
				<option value="11:30 AM">
				<option value="12:00 PM">
				<option value="12:30 PM">
				<option value="01:00 PM">
				<option value="01:30 PM">
				<option value="02:00 PM">
				<option value="02:30 PM">
				<option value="03:00 PM">
				<option value="03:30 PM">
				<option value="04:00 PM">
				<option value="04:30 PM">
				<option value="05:00 PM">
				<option value="05:30 PM">
				<option value="06:00 PM">
				<option value="06:30 PM">
				<option value="07:00 PM">
				<option value="07:30 PM">
				<option value="08:00 PM">
				<option value="08:30 PM">
				<option value="09:00 PM">
				<option value="09:30 PM">
				<option value="10:00 PM">
				<option value="10:30 PM">
				<option value="11:00 PM">
				<option value="11:30 PM">
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
					'title'         => '',
					'monday-open'   => '',
					'monday-close'  => '',
					'tuesday-open'  => '',
					'tuesday-close' => '',
					'wednesday-open'  => '',
					'wednesday-close' => '',
					'thursday-open'  => '',
					'thursday-close' => '',
					'friday-open'  => '',
					'friday-close' => '',
					'saturday-open'  => '',
					'saturday-close' => '',
					'sunday-open'  => '',
					'sunday-close' => '',
				)
			);

			foreach ( $new_instance as $field => $val ) {
				$instance[ $field ] = sanitize_text_field( $val );
			}

			//$instance['title']         = sanitize_text_field( $new_instance['title'] );
			//$instance['monday-open']   = sanitize_text_field( $new_instance['monday-open'] );
			//$instance['monday-close']  = sanitize_text_field( $new_instance['monday-close'] ); 
			//$instance['tuesday-open']  = sanitize_text_field( $new_instance['tuesday-open'] );
			//$instance['tuesday-close'] = sanitize_text_field( $new_instance['tuesday-close'] );

			return $instance;
		}
	}
}