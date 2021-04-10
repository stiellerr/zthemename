<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content"> data-content="f879"
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zthemename
 */

// get theme options.
$nav_theme        = get_theme_mod( 'nav_theme', 'navbar-light' );
$nav_btn_type     = get_theme_mod( 'nav_btn_type', false );
$zthemename_phone = get_theme_mod( 'zthemename_phone', '' );

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'min-vh-100 d-flex flex-column' ); ?>>
<?php wp_body_open(); ?>
<header>
	<nav id="site-navigation" class="navbar navbar-expand-md <?php echo esc_html( $nav_theme ); ?>">
		<div class="container">
			<?php
			the_custom_logo();
			if ( $zthemename_phone || is_customize_preview() ) :
				?>
				<div class="wp-block-button order-md-last me-3 me-sm-0<?php $nav_btn_type && printf( ' %s', esc_html( $nav_btn_type ) ); ?>">
					<?php $zthemename_phone && printf( '<a class="wp-block-button__link" href="tel:%1$s"><i class="fas fa-phone-alt"data-content="f879">&nbsp;</i>%1$s</a>', esc_html( $zthemename_phone ) ); ?>			
				</div>
			<?php endif; ?>
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
						'menu_class'      => 'navbar-nav',
						'fallback_cb'     => false,  
					)
				);
				?>
		</div>
	</nav>
</header>

