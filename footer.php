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
				<div class="footer-nav mt-3">
					<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'menu-2',
								'depth'           => 1,
								'fallback_cb'     => false,
								'menu_class'      => 'nav',
							)
						);
					?>
					<div>
						<?php
						/* translators: 1: Theme name, 2: Current year. */
						printf( esc_html__( 'Powered by %1$s © %2$s.', 'zthemename' ), '<a href="https://zthemename.com/">zthemename</a>', esc_attr( date( 'Y' ) ) );
						?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
