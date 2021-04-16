<?php
/**
 * Zthemename page excerpt custom widget
 *
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Page_Excerpt_Widget' ) ) {

	/**
	 * Custom class used to implement the Page Excerpt widget.
	 */
	class Zthemename_Page_Excerpt_Widget extends WP_Widget {

		/**
		 * Sets up a new page excerpt widget instance.
		 */
		public function __construct() {
			parent::__construct(
				'zthemename_page_excerpt',
				esc_html__( 'Page Excerpt', 'zthemename' ),
				array(
					'description'                 => esc_html__( 'Displays an excert from the selected page', 'zthemename' ),
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Outputs the content for the current Page Excerpt widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {


			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Page Excerpt', 'zthemename' );

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
	
			$excerpt = ! empty( $instance['page_id'] ) ? get_the_excerpt( intval( $instance['page_id'] ) ) : esc_html__( 'Welcome to DIY Marketer. This the excerpt widget. Select a page to retrieve its excerpt. If no excerpt is found, we will attempt to create one for you automatically.', 'zthemename' );
	
			echo $args['before_widget'];
	
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
	
			if ( $excerpt ) {
				echo '<p class="has-text-align-justify">' . $excerpt . '</p>';
			}
	
			echo $args['after_widget'];

		}

		/**
		 * Handles updating settings for the page excerpt widget instance.
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = array();
	
			if ( empty( $old_instance ) ) {
			
				if ( empty( $new_instance['page_id'] ) ) {
					$instance['title'] = sanitize_text_field( $new_instance['title'] );
				} else {
					$instance['title'] = get_the_title( $new_instance['page_id'] );
				}           
			} else {
	
				if ( $new_instance['page_id'] != $old_instance['page_id'] ) {
					if ( $new_instance['page_id'] > 1 ) {
						$instance['title'] = get_the_title( $new_instance['page_id'] );
					} else {
						$instance['title'] = esc_html__( 'Page Excerpt', 'zthemename' );
					}
				} else {
					$instance['title'] = sanitize_text_field( $new_instance['title'] );
				}    
			}
	
			$instance['page_id'] = intval( $new_instance['page_id'] );
	
			return $instance;
		}

		/**
		 * Outputs the settings form for the Page Exceprt widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {

			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'     => esc_html__( 'Page Excerpt', 'zthemename' ),
					'page_id'   => 0,
				)
			);
	
			$pages = get_pages();
			
			?>
	
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'zthemename' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
	
			<p>
				<label for="<?php echo $this->get_field_id( 'page_id' ); ?>"><?php _e( 'Select Page:', 'zthemename' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'page_id' ); ?>" name="<?php echo $this->get_field_name( 'page_id' ); ?>" class="widefat">
					<option value="0"><?php _e( '&mdash; Select &mdash;', 'zthemename' ); ?></option>
					<?php foreach ( $pages as $page ) : ?>
						<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $instance['page_id'], $page->ID ); ?>>
							<?php echo esc_html( $page->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
	
			<?php
		}
	}
}

/**
 * Register Custom widget class used to implement the Page Excerpt widget.
 */
function zthemename_register_page_excerpt_widget() {
	register_widget( 'Zthemename_Page_Excerpt_Widget' );
}

add_action( 'widgets_init', 'zthemename_register_page_excerpt_widget' );

