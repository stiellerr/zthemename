/* global wp, Color, jQuery */
/**
 * File customize-controls.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

const setHeadFootButtonOutline = (head_foot, accent) => {
    const head_foot_bg_color = new Color(head_foot);
    const acc_color = new Color(accent);

    const lum = head_foot_bg_color.getDistanceLuminosityFrom(acc_color);

    let outline = 4.5 > lum ? true : false;

    wp.customize("header_footer_button_outline").set(outline);
};

wp.customize.bind("ready", () => {
    // ready...
    console.log("customize controls ready...");

    // Add color control for navbar.
    wp.customize.control.add(
        new wp.customize.ColorControl("header_footer_background_color", {
            section: "colors",
            label: "Header &amp; Footer Background Color",
            setting: "header_footer_background_color"
        })
    );

    // Add color control for accent.
    wp.customize.control.add(
        new wp.customize.ColorControl("accent_color", {
            section: "colors",
            label: "Accent Color",
            setting: "accent_color"
        })
    );

    wp.customize("header_footer_background_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            const head_foot_bg_color = new Color(to);
            wp.customize("nav_theme").set(
                head_foot_bg_color.getMaxContrastColor()._color ? "navbar-dark" : "navbar-light"
            );
            setHeadFootButtonOutline(to, wp.customize.get().accent_color);
        });
    });

    wp.customize("accent_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            setHeadFootButtonOutline(wp.customize.get().header_footer_background_color, to);
        });
    });
});
