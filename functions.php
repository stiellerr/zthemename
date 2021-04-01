<?php
/**
 * Zthemename functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zthemename
 */

// useful function for witing to the log.
if ( ! function_exists( 'write_log ' ) ) {
	/**
	 * Useful function for witing to the log..
	 *
	 * @param array $log Array of the CSS classes that are applied to the menu <ul> element.
	 */
	function write_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', wp_get_theme()->get( 'Version' ) );
}

if ( ! function_exists( 'zthemename_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function zthemename_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on zthemename, use a find and replace
		 * to change 'zthemename' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'zthemename', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'zthemename' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Editor color palette.
		$primary = '#0d6efd';

		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => esc_html__( 'Primary', 'zthemename' ),
					'slug'  => 'primary',
					'color' => $primary,
				),
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'zthemename_setup' );

/**
 * Register and Enqueue Scripts and Styles.
 */
function zthemename_scripts() {
	wp_enqueue_style(
		'zthemename',
		get_template_directory_uri() . '/dist/css/style.css',
		array(),
		_S_VERSION
	);
	// wp_dequeue_style( 'wp-block-library' );.

	wp_enqueue_script(
		'zthemename',
		get_template_directory_uri() . '/dist/js/script.js',
		array(),
		_S_VERSION,
		true
	);
	// wp_deregister_script( 'wp-embed' );.
}

add_action( 'wp_enqueue_scripts', 'zthemename_scripts' );

/**
 * Register and Enqueue Admin Scripts and Styles.
 */
function zthemename_admin_scripts() {
	wp_enqueue_style(
		'zthemename',
		get_template_directory_uri() . '/dist/css/admin.css',
		array(),
		_S_VERSION
	);

	wp_enqueue_script(
		'zthemename',
		get_template_directory_uri() . '/dist/js/admin.js',
		array(),
		_S_VERSION,
		false
	);
}

add_action( 'admin_enqueue_scripts', 'zthemename_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function zthemename_editor_assets() {
	// Enqueue the editor styles.
	wp_enqueue_style(
		'zthemename-editor',
		get_template_directory_uri() . '/dist/editor/css/style.css',
		// ["wp-edit-blocks"],.
		array(),
		_S_VERSION
	);

	// Enqueue the editor script.
	wp_enqueue_script(
		'zthemename-editor',
		get_template_directory_uri() . '/dist/editor/js/script.js',
		array(
			'jquery',
			'underscore',
			'lodash',
			'wp-block-editor',
			'wp-rich-text',
			'wp-components',
			'wp-i18n',
			'wp-dom',
			// "wp-primitives",
			'wp-element',
			'wp-data',
			'wp-compose',
			'wp-dom-ready',
			
			/*
			'wp-hooks',
			'wp-element',
			'wp-data',
			'wp-block-editor',
			'wp-rich-text',
			'wp-blocks',
			'wp-i18n',
			'wp-block-editor',
			'wp-components',
			'lodash',
			'wp-plugins',
			'wp-edit-post',
			'wp-compose'
			'jquery',
			'wp-compose',
			'wp-data',
			'wp-editor',
			'wp-element',
			'wp-rich-text',
			*/
		),
		_S_VERSION,
		true
	);

	// load font awesome data to browser...
	wp_localize_script(
		'zthemename-editor',
		'fa_icons',
		array(
			'data' => get_template_directory_uri() . '/dist/data/icons.json',
		)
	);
}

add_action( 'enqueue_block_editor_assets', 'zthemename_editor_assets' );

/**
 * Filters the HTML attributes applied to a menu item’s anchor element.
 *
 * @param string[] $classes Array of the CSS classes that are applied to the menu <ul> element.
 * @param WP_Post  $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return array
 */
function zthemename_nav_menu_css_class( $classes, $item, $args, $depth ) {
	if ( 'menu-1' === $args->theme_location ) {
		$classes[] = 'nav-item';
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'dropdown';
		}
	}
	return $classes;
}

add_filter( 'nav_menu_css_class', 'zthemename_nav_menu_css_class', 10, 4 );

/**
 * Filters the HTML attributes applied to a menu item’s anchor element.
 *
 * @param array    $atts The HTML attributes applied to the menu item's <a> element, empty strings are ignored.
 * @param WP_Post  $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return array
 */
function zthemename_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( 'menu-1' === $args->theme_location ) {
		if (
			in_array( 'menu-item-has-children', $item->classes, true ) &&
			0 === $depth &&
			$args->depth > 1
		) {
			$atts['href']           = '#';
			$atts['data-bs-toggle'] = 'dropdown';
			$atts['aria-expanded']  = 'false';
			$atts['role']           = 'button';
			$atts['class']          = 'nav-link dropdown-toggle';
			// $atts['id'] = 'menu-item-dropdown-' . $item->ID;
		} else {
			if ( $depth > 0 ) {
				$atts['class'] = 'dropdown-item';
			} else {
				$atts['class'] = 'nav-link';
			}
		}
		if ( $item->current ) {
			$atts['class'] .= ' active';
		}
	}
	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'zthemename_nav_menu_link_attributes', 10, 4 );

/**
 * Filters the CSS class(es) applied to a menu list element.
 *
 * @param string[] $classes Array of the CSS classes that are applied to the menu <ul> element.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return array
 */
function zthemename_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	if ( 'menu-1' === $args->theme_location ) {
		$classes = array( 'dropdown-menu' );
	}
	return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'zthemename_nav_menu_submenu_css_class', 10, 3 );

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

// Custom color classes.
require get_template_directory() . '/classes/class-zthemename-custom-colors.php';
new Zthemename_Custom_Colors();
