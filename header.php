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

// get theme options.
$schema                       = get_theme_mod( 'schema' );
$zthemename_phone             = isset( $schema['phone'] ) ? $schema['phone'] : false;
$zthemename_internation_phone = isset( $schema['telephone'] ) ? $schema['telephone'] : false;
$button_outline               = get_theme_mod( 'header_footer_button_outline' ) ? ' is-style-outline' : '';

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
<header class="site-header">
	<nav id="site-navigation" class="navbar navbar-expand-md <?php echo esc_html( get_theme_mod( 'nav_theme', 'navbar-light' ) ); ?>">
		<div class="container">
			<?php
			the_custom_logo();
			if ( $zthemename_phone ) : 
				?>
			<div class="wp-block-button order-md-last<?php echo esc_attr( $button_outline ); ?>">
				<a class="wp-block-button__link" href="tel:<?php echo esc_attr( $zthemename_internation_phone ); ?>"><i class="fas fa-phone-alt"data-content="f879">&nbsp;</i><?php echo esc_attr( $zthemename_phone ); ?></a>
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

