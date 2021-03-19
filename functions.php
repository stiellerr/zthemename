<?php
/**
 * zthemename functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zthemename
 */

// useful function for witing to the log
if (!function_exists("write_log ")) {
    function write_log($log) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

if (!defined("_S_VERSION")) {
    // Replace the version number of the theme on each release.
    define("_S_VERSION", wp_get_theme()->get("Version"));
}

if (!function_exists("zthemename_setup")):
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
        load_theme_textdomain("zthemename", get_template_directory() . "/languages");

        // Add default posts and comments RSS feed links to head.
        add_theme_support("automatic-feed-links");

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support("title-tag");

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support("post-thumbnails");

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus([
            "menu-1" => esc_html__("Primary", "zthemename")
        ]);

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support("html5", [
            "search-form",
            "comment-form",
            "comment-list",
            "gallery",
            "caption",
            "style",
            "script"
        ]);

        // Set up the WordPress core custom background feature.
        add_theme_support(
            "custom-background",
            apply_filters("zthemename_custom_background_args", [
                "default-color" => "ffffff",
                "default-image" => ""
            ])
        );

        // Editor color palette.
        $primary = "#0d6efd";

        add_theme_support("editor-color-palette", [
            [
                "name" => esc_html__("Primary", "zthemename"),
                "slug" => "primary",
                "color" => $primary
            ]
        ]);

        // Add theme support for selective refresh for widgets.
        add_theme_support("customize-selective-refresh-widgets");

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support("custom-logo", [
            "height" => 250,
            "width" => 250,
            "flex-width" => true,
            "flex-height" => true
        ]);
    }
endif;
add_action("after_setup_theme", "zthemename_setup");

/**
 * Register and Enqueue Scripts and Styles.
 */
function zthemename_scripts() {
    wp_enqueue_style(
        "zthemename",
        get_template_directory_uri() . "/dist/css/style.css",
        [],
        _S_VERSION
    );
    wp_dequeue_style("wp-block-library");

    wp_enqueue_script(
        "zthemename",
        get_template_directory_uri() . "/dist/js/script.js",
        [],
        _S_VERSION,
        false
    );
    //wp_deregister_script( 'wp-embed' );
}

add_action("wp_enqueue_scripts", "zthemename_scripts");

/**
 * Register and Enqueue Admin Scripts and Styles.
 */
function zthemename_admin_scripts() {
    wp_enqueue_style(
        "zthemename",
        get_template_directory_uri() . "/dist/css/admin.css",
        [],
        _S_VERSION
    );

    wp_enqueue_script(
        "zthemename",
        get_template_directory_uri() . "/dist/js/admin.js",
        [],
        _S_VERSION,
        false
    );
}

add_action("admin_enqueue_scripts", "zthemename_admin_scripts");

/**
 * Enqueue block editor assets.
 */
function zthemename_editor_assets() {
    // Enqueue the editor styles.
    wp_enqueue_style(
        "zthemename-editor",
        get_template_directory_uri() . "/dist/editor/css/style.css",
        //["wp-edit-blocks"],
        [],
        _S_VERSION
    );

    // Enqueue the editor script.
    wp_enqueue_script(
        "zthemename-editor",
        get_template_directory_uri() . "/dist/editor/js/script.js",
        [
            "jquery",
            "underscore",
            "lodash",
            "wp-block-editor",
            "wp-rich-text",
            "wp-components",
            "wp-i18n",
            "wp-dom",
            //"wp-primitives",
            "wp-element",
            "wp-data",
            "wp-compose",
            "wp-dom-ready"
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
        ],
        _S_VERSION,
        true
    );

    // load font awesome data to browser...
    wp_localize_script("zthemename-editor", "fa_icons", [
        "data" => get_template_directory_uri() . "/dist/data/icons.json"
    ]);
}

add_action("enqueue_block_editor_assets", "zthemename_editor_assets");
