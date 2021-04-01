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

    // Add color control for accent.
    wp.customize.control.add(
        new wp.customize.ColorControl("accent_color", {
            section: "colors",
            label: "Accent Color.",
            setting: "accent_color"
        })
    );

    wp.customize("nav_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            //var c = new Color(value);
            //console.log(c.getReadableContrastingColor());

            const nav_color = new Color(to);
            //const acc_color = new Color(wp.customize.get().accent_color);

            //const c = nav_color.getDistanceLuminosityFrom(acc_color);

            //if (4.5 > c) {
            //console.log(c);

            //$(".wp-block-button").addClass("is-style-outline"
            // window.parent[0].$(".wp-block-button").addClass("is-style-outline");
            //} else {
            //$(".wp-block-button").removeClass("is-style-outline");
            //}

            //'wp-color-picke

            //console.log(c);

            //k.getDistanceLuminosityFrom('0000')
            //const accent_color = new Color(to);

            wp.customize("nav_theme").set(
                nav_color.getMaxContrastColor()._color ? "navbar-dark" : "navbar-light"
            );
        });
    });
});
