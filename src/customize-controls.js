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
    /*
    wp.customize.section.add(
        new wp.customize.Section("footer_options", {
            title: "Footer Options.",
            //panel: "colors",
            //customizeAction: "Customizing ▸ Theme Options",
            customizeAction: "Customizing",
            priority: -1
        })
    );
    */
    //wp.customize.control("blogname").section("colors");
    // Site Title Color Setting.
    wp.customize.add(
        new wp.customize.Setting("nav_theme", "light", {
            transport: "postMessage"
        })
    );

    wp.customize.add(
        new wp.customize.Setting("nav_color", "#fff", {
            transport: "postMessage"
        })
    );
    // Add checkbox control.
    wp.customize.control.add(
        new wp.customize.ColorControl("nav_color", {
            //new wp.customize.Control({
            setting: "nav_color",
            //type: "text",
            section: "colors",
            //panel: "hannover_example_section",
            label: "Navbar Color."
            //description: "this is a description."
        })
    );

    wp.customize("nav_color", (value) => {
        // Add a listener for accent-color changes.
        value.bind((to) => {
            const nav_color = new Color(to);
            wp.customize("nav_theme").set(
                nav_color.getMaxContrastColor()._color ? "dark" : "light"
            );
        });
    });
});
