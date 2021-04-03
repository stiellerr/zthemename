<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zthemename
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header>
	<?php $nav_theme = get_theme_mod( 'nav_theme', 'navbar-light' ); ?>
	<nav id="site-navigation" class="navbar navbar-expand-md <?php echo $nav_theme; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
		<div class="container">
			<?php the_custom_logo(); ?>
			<div class="wp-block-button order-md-last me-3 me-sm-0">
				<a class="wp-block-button__link" href="tel:0275457737" style="border-radius:4px;"><i class="fas fa-phone-alt" data-content="f879">&nbsp;</i>027 545 7737</a>
			</div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'menu-1',
						'depth'           => 2,
						'container'       => 'div',
						'container_id'    => 'navbarNav',
						'container_class' => 'collapse navbar-collapse',
						'menu_class'      => 'navbar-nav me-auto',
						'fallback_cb'     => false,  
					)
				);
				?>
		</div>
	</nav>

</header>
<div class="reece">
<?php header_image(); ?>
		</div>
<!--
<header style="background-color: #c2e5c0; padding-top: 1.5rem; border-bottom: 1px solid #dee2e6;">
	<div class="container">
		<div class="row">
		Hello World
		</div>
	</div>
</header>
-->
<div class="container">
	<div class="row">
		<main class="col">
			<?php 
			if ( have_posts() ) :
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			endif; 
			?>
		</main>
		<!--
		<aside class="col-md-4 col-lg-3">
				<div style="border: 1px solid #dee2e6; padding: 1rem; border-radius: 0.25rem; position: sticky; top: 4rem;">
					<h3>Contact Form</h3>
					<input type="text">
				</div>
		</aside>
			-->
	</div>
</div>
