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
	<div class="container wp-block-columns <?php echo esc_html( get_theme_mod( 'nav_theme', 'navbar-light' ) ); ?>">
		<?php if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
			<!--<div class="wp-block-columns">-->
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			<!--</div>-->
		<?php } ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
