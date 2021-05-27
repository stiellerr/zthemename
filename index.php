<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zthemename
 */

get_header();
?>

	<div id="primary" class="container wp-block-columns my-3">
		<main class="wp-block-column">

			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					* Include the Post-Type-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Type name) and that will be used instead.
					*/
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;

				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
			<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="width: 100%;">
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="http://localhost/wp-content/uploads/2019/02/richard-stieller-1.png" class="d-block w-100" alt="...">
					</div>
					<div class="carousel-item">
						<img src="http://localhost/wp-content/uploads/2019/01/charley-jean-brown-1.png" class="d-block w-100" alt="...">
					</div>
					<div class="carousel-item">
						<img src="http://localhost/wp-content/uploads/2019/02/sarah-good-1.png" class="d-block w-100" alt="...">
					</div>
				</div>
			</div>
		</main><!-- #main -->
		<?php get_sidebar(); ?>

	</div>

<?php
get_footer();
