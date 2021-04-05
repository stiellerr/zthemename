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


<footer class="mt-auto">
	<div class="container">
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
			<div class="wp-block-columns">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
		<?php } ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
