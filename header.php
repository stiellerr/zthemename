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

<body 
<?php
body_class();
?>
>
<?php
/*wp_body_open();*/
?>
<!--
<div style="font-weight: bold;">This is the header!</div>-->
<header>

	
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="#">
				<img src="http://localhost/wp-content/uploads/automation-and-operational-technology-logo.png" style="max-width: 100%; max-height: 60px;">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="#">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Link</a>
					</li>
					<!--
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="#">Action</a></li>
							<li><a class="dropdown-item" href="#">Another action</a></li>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="#">Something else here</a></li>
						</ul>
					</li>
-->
					<li class="nav-item">
						<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
					</li>
				</ul>
				<a class="wp-block-button__link">0800 007 003</a>
			</div>
		</div>
	</nav>
	<div>dynamic</div>
	<!--
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">

			<a class="navbar-brand" href="#">-->
			<?php
			/* the_custom_logo(); */
			?>
			<!--</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<?php
			/* 
			TODO
			 wp_nav_menu([
				"theme_location" => "menu-1",
				"depth" => 2,
				"container" => "div",
				"container_id" => "navbarNav",
				"container_class" => "collapse navbar-collapse",
				"menu_class" => "navbar-nav me-auto",
				"fallback_cb" => false
				
				"fallback_cb" => function () {
					echo "&nbsp";
				}
				
			]);
			*/
			?>
		</div>
	</nav> -->

</header>

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

</body>
</html>
