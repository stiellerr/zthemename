/* global wp, Color, jQuery, _ */
/**
 * File customize-controls.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

import Zthemename_Colors from "./js/color-calculations.js";

const updateAccentColors = (accent, headerFooterBackground) => {
    // Get the current value for our accessible colors, and make sure it's an object.
    let value = wp.customize("accent_colors").get();
    value = _.isObject(value) && !_.isArray(value) ? value : {};

    let colors = new Zthemename_Colors(accent);

    value["content"] = colors.init("#ffffff");
    value["header-footer"] = colors.init(headerFooterBackground);

    wp.customize("accent_colors").set(value);
    wp.customize("header_footer_button_outline").set(colors.isOutline());
};

wp.customize.bind("ready", () => {
    // ready...
    console.log("customize controls ready...");

    // hide site title.
    wp.customize.control("blogname").toggle();

    // hide tagline.
    wp.customize.control("blogdescription").toggle();

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
            updateAccentColors(wp.customize.get().header_footer_background_color, to);
        });
    });

    wp.customize("accent_color", (value) => {
        // Add a listener for navbar color changes.
        value.bind((to) => {
            updateAccentColors(to, wp.customize.get().header_footer_background_color);
        });
    });
});
