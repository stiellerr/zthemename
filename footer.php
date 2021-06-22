<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zthemename
 */

?>	

<footer class="site-footer mt-auto">
	<div class="container <?php echo esc_html( get_theme_mod( 'nav_theme', 'navbar-light' ) ); ?>">
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
			<div class="wp-block-columns">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php } ?>
		<?php  
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'depth'          => 1,
				'fallback_cb'    => false,
				'menu_class'     => 'nav',
				'menu_id'        => 'menu-footer',
			)
		); 
		?>
		<div>hello world</div>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
