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
wp.customize("nav_color", (value) => {
    value.bind((to) => {
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-nav:\s)#[\d\w]+/, `$1${to}`);
        });
    });
});

// Navbar theme.
wp.customize("nav_theme", (value) => {
    value.bind((to) => {
        $("nav").removeClass("navbar-light navbar-dark").addClass(to);
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
wp.customize("nav_btn_type", (value) => {
    value.bind((to) => {
        // remove outline class
        $(".wp-block-button").removeClass("is-style-outline");
        if (to) {
            $(".wp-block-button").addClass(to);
        }
    });
});
