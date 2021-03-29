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
        $("nav").css("background-color", to);
    });
});

// Navbar theme.
wp.customize("nav_theme", (value) => {
    value.bind((to) => {
        $("nav").removeClass("navbar-light navbar-dark").addClass(to);
    });
});
