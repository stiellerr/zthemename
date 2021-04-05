/* global wp, Color, jQuery */
/**
 * File customize-controls.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

const setNavButtonType = (nav, accent) => {
    const nav_color = new Color(nav);
    const acc_color = new Color(accent);

    const lum = nav_color.getDistanceLuminosityFrom(acc_color);

    let outline = false;

    if (4.5 > lum) {
        outline = "is-style-outline";
    }

    wp.customize("nav_btn_type").set(outline);
};

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
            const nav_color = new Color(to);
            wp.customize("nav_theme").set(
                nav_color.getMaxContrastColor()._color ? "navbar-dark" : "navbar-light"
            );
            setNavButtonType(to, wp.customize.get().accent_color);
        });
    });

    wp.customize("accent_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            setNavButtonType(wp.customize.get().nav_color, to);
        });
    });
});
