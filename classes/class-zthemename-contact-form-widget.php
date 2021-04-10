<?php
/**
 * Zthemename contact form custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'zthemename_Contact_Form_Widget' ) ) {
	
	/**
	 * Core class used to implement the Calendar widget.
	 */
	class Zthemename_Contact_Form_Widget extends WP_Widget {

		/**
		 * Sets up a new contact form widget instance.
		 */
		public function __construct() {
			parent::__construct(
				'zthemename_contact_form',
				esc_html__( 'Contact Form', 'zthemename' ),
				array(
					'description'                 => esc_html__( 'Displays a contact form', 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Outputs the content for the current Contact Form widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {
		
			// $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__('Contact Form', 'zthemename');
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Form.', 'zthemename' );
		
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$message = isset( $instance['message'] ) ? $instance['message'] : 1;
			$name    = isset( $instance['name'] ) ? $instance['name'] : 1;
			$phone   = isset( $instance['phone'] ) ? $instance['phone'] : 0;
			$email   = isset( $instance['email'] ) ? $instance['email'] : 1;

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			?>

			<form class="contact-form needs-validation" novalidate>
				<?php /* echo $title ? $args['before_title'] . $title . $args['after_title'] : ''; */ ?>
				<!--<input type="hidden" name="action" value="send_form" />-->
				<?php if ( $message ) { ?>            
					<div class="mb-2"><!-- message -->
						<textarea class="form-control form-control-sm" name="message" id="message" placeholder="How can we help?" required></textarea>
						<div class="invalid-feedback"><?php esc_html_e( 'message required', 'zthemename' ); ?></div>
					</div>
				<?php } ?>
				<?php if ( $name ) { ?>
					<div class="mb-2"><!-- name -->
						<input type="text" class="form-control form-control-sm" name="name" id="name" placeholder="Your name." required />
						<div class="invalid-feedback"><?php esc_html_e( 'name required', 'zthemename' ); ?></div>
					</div>
				<?php } ?>
				<?php if ( $phone ) { ?>   
					<div class="mb-2"><!-- Phone -->
						<input type="text" class="form-control form-control-sm" name="phone" id="phone" placeholder="Phone number." required />
						<div class="invalid-feedback"><?php esc_html_e( 'phone number required', 'zthemename' ); ?></div>
					</div>
				<?php } ?>
				<?php if ( $email ) { ?>
					<div class="mb-2"><!-- Email -->
						<input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email." required />
						<div class="invalid-feedback"><?php esc_html_e( 'valid email required', 'zthemename' ); ?></div>
					</div>
				<?php } ?>
				<div class="wp-block-button">
					<button type="submit" class="wp-block-button__link"><?php esc_html_e( 'Submit.', 'zthemename' ); ?></button>
				</div>
				<small>
					<i class="fas fa-lock" data-content="f023"></i>&nbsp;<?php esc_html_e( "we'll never share your information with anyone.", 'zthemename' ); ?>
				</small>

			</form>

			<?php

			echo $args['after_widget'];
		}

		/**
		 * Outputs the settings form for the Contact Form widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'     => esc_html__( 'Contact Form', 'zthemename' ),
					'message'   => true,
					'name'      => true,
					'phone'     => false,
					'email'     => true,
				)
			);
		
			/*
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__('Contact Form', 'zthemename');
			$message = isset( $instance['message'] ) ? $instance['message'] : true;
			$name = isset( $instance['name'] ) ? $instance['name'] : true;
			$phone = isset( $instance['phone'] ) ? $instance['phone'] : false;
			$email = isset( $instance['email'] ) ? $instance['email'] : true;
			*/
			// $message = isset( $instance['message'] ) ? 1 : 1;
			// $name = isset( $instance['name'] ) ? $instance['name'] : true;
			// $phone = isset( $instance['phone'] ) ? $instance['phone'] : false;
			// $email = isset( $instance['email'] ) ? $instance['email'] : true;

			?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p><?php esc_html_e( 'Fields to display:', 'zthemename' ); ?><br>
			<input class="checkbox" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>" type="checkbox" <?php checked( $instance['message'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'message' ); ?>"><?php esc_html_e( 'message', 'zthemename' ); ?></label>
			<br>  
			<input class="checkbox" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="checkbox" <?php checked( $instance['name'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php esc_html_e( 'name', 'zthemename' ); ?></label>
			<br>
			<input class="checkbox" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="checkbox" <?php checked( $instance['phone'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'phone number', 'zthemename' ); ?></label>
			<br>
			<input class="checkbox" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="checkbox" <?php checked( $instance['email'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php esc_html_e( 'email', 'zthemename' ); ?></label>
		</p>
			<?php
		}

		/**
		 * Handles updating settings for the current Links widget instance.
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
					'title'     => '',
					'message'   => 0,
					'name'      => 0,
					'phone'     => 0,
					'email'     => 0,
				)
			);

			$instance['title']   = sanitize_text_field( $new_instance['title'] );
			$instance['message'] = $new_instance['message'] ? 1 : 0;
			$instance['name']    = $new_instance['name'] ? 1 : 0;
			$instance['phone']   = $new_instance['phone'] ? 1 : 0;
			$instance['email']   = $new_instance['email'] ? 1 : 0;
			
			/*
			$instance['message'] = boolval( $new_instance['message'] );
			$instance['name']    = boolval( $new_instance['name'] );
			$instance['phone']   = boolval( $new_instance['phone'] );
			$instance['email']   = boolval( $new_instance['email'] );
			*/


			return $instance;
		}
	}
}
