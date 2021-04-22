/* global wp, jQuery _ */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

const updateAccentStyles = () => {
    $("#zthemename-inline-css").html((index, currentcontent) => {
        //
        const colors = window.parent.wp.customize("accent_colors").get();
        // each =>
        _.each(colors, (obj, context) => {
            _.each(obj, (val, key) => {
                let regex = new RegExp(`(--global--color-${context}-${key}:\\s)#[\\d\\w]+`);
                currentcontent = currentcontent.replace(regex, `$1${val}`);
            });
        });
        //
        return currentcontent;
    });
};

// Navbar color.
wp.customize("header_footer_background_color", (value) => {
    value.bind((to) => {
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-head-foot:\s)#[\d\w]+/, `$1${to}`);
        });
        updateAccentStyles();
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
    value.bind(() => {
        updateAccentStyles();
        /*
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-accent:\s)#[\d\w]+/, `$1${to}`);
        });
        */
    });
});

// Navbar color.
/*
wp.customize("header_footer_button_outline", (value) => {
    value.bind((to) => {
        const new_class = to ? "is-style-outline" : "";
        // remove outline class
        $(".site-header .wp-block-button, .site-footer .wp-block-button")
            .removeClass("is-style-outline")
            .addClass(new_class);
    });
});
*/
