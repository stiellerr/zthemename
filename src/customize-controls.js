/* global wp, Color, jQuery */
/**
 * File customize-controls.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

wp.customize.bind("ready", () => {
    // ready...
    console.log("customize controls ready...");

    // Add color control for navbar.
    wp.customize.control.add(
        new wp.customize.ColorControl("nav_color", {
            section: "colors",
            label: "Navbar Color.",
            setting: "nav_color"
        })
    );

    wp.customize("nav_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            const nav_color = new Color(to);
            wp.customize("nav_theme").set(
                nav_color.getMaxContrastColor()._color ? "navbar-dark" : "navbar-light"
            );
        });
    });
});
