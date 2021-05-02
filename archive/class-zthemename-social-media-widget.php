<?php
/**
 * Zthemename social media custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Social_Media_Widget' ) ) {

	/**
	 * Core class used to implement the Calendar widget.
	 */
	class Zthemename_Social_Media_Widget extends WP_Widget {

		/**
		 * Sets up a new contact form widget instance.
		 */
		public function __construct() {
			//
			parent::__construct(
				'zthemename_social_media',
				esc_html__( 'Social Media', 'zthemename' ),
				array(
					'description'                 => esc_html__( "Displays your business' social media links", 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
			$options = get_option( 'zthemename_options' );
			$this->socials = $options ? $options['social_media'] : false;
		}

		/**
		 * Outputs the content for the current Social Media widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {

			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Social Media.', 'zthemename' );
		
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// bail if no links found.
			if ( $this->socials && ( $this->socials['facebook'] || $this->socials['instagram'] || $this->socials['twitter'] || $this->socials['youtube'] )) {
				
				echo '<div>';
				
				$this->socials['facebook'] && 
					printf(
						'<div><a href="%1$s" target="_blank"><i class="fab fa-facebook-f fa-fw" data-content="f39e"></i>%2$s</a></div>',
						esc_url( $this->socials['facebook'] ),
						esc_html__( 'Like Us On Facebook', 'zthemename' )
					);
			
				$this->socials['instagram'] && 
					printf(
						'<div><a href="%1$s" target="_blank"><i class="fab fa-instagram fa-fw" data-content="f16d"></i>%2$s</a></div>',
						esc_url( $this->socials['instagram'] ),
						esc_html__( 'Follow Us On Instagram', 'zthemename' )
					);
				
				$this->socials['twitter'] && 
					printf(
						'<div><a href="%1$s" target="_blank"><i class="fab fa-twitter fa-fw" data-content="f099"></i>%2$s</a></div>',
						esc_url( $this->socials['twitter'] ),
						esc_html__( 'Follow Us On Twitter', 'zthemename' )
					);

				$this->socials['youtube'] &&
					printf(
						'<div><a href="%1$s" target="_blank"><i class="fab fa-youtube fa-fw" data-content="f167"></i>%2$s</a></div>',
						esc_url( $this->socials['youtube'] ),
						esc_html__( 'Our Youtube Channel', 'zthemename' )
				);			

				echo '</div>';
			}
			echo $args['after_widget'];
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
			$instance          = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
	
			return $instance;
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
					'title'     => esc_html__( 'Social Media', 'zthemename' ),
				)
			);
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php esc_html_e( 'Facebook url:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" type="url" value="<?php echo $this->socials ? esc_url( $this->socials['facebook'] ) : '' ?>" readonly>	
				<br/>
				<label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php esc_html_e( 'instagram url:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" type="url" value="<?php echo $this->socials ? esc_url( $this->socials['instagram'] ) : '' ?>" readonly>	
				<br/>
				<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php esc_html_e( 'twitter url:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" type="url" value="<?php echo $this->socials ? esc_url( $this->socials['twitter'] ) : '' ?>" readonly>
				<br/>
				<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php esc_html_e( 'youtube url:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" type="url" value="<?php echo $this->socials ? esc_url( $this->socials['youtube'] ) : '' ?>" readonly>		
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
	}
}

