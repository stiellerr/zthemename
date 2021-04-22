/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

// Navbar color.
wp.customize("header_footer_background_color", (value) => {
    value.bind((to) => {
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-head-foot:\s)#[\d\w]+/, `$1${to}`);
        });
        var zzz = wp.customize("accent_colors").get();
        console.log(zzz);
    });
});

// Navbar theme.
wp.customize("nav_theme", (value) => {
    value.bind((to) => {
        $(".site-header nav, .site-footer .container")
            .removeClass("navbar-light navbar-dark")
            .addClass(to);
    });
});

// Navbar color.
wp.customize("accent_color", (value) => {
    value.bind((to) => {
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-accent:\s)#[\d\w]+/, `$1${to}`);
        });
    });
});

// Navbar color.
wp.customize("header_footer_button_outline", (value) => {
    value.bind((to) => {
        const new_class = to ? "is-style-outline" : "";
        // remove outline class
        $(".site-header .wp-block-button, .site-footer .wp-block-button")
            .removeClass("is-style-outline")
            .addClass(new_class);
    });
});

// Navbar color.
wp.customize("accent_colors", (value) => {
    value.bind((to) => {
        console.log("zzzz");
    });
});
