/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

const $ = jQuery;

/*
const getButtonColor = (nav, accent) => {
    const Color = window.parent.Color;

    const nav_color = new Color(nav);
    const acc_color = new Color(accent);

    const lum = nav_color.getDistanceLuminosityFrom(acc_color);

    // remove outline class
    $(".wp-block-button").removeClass("is-style-outline");

    if (4.5 > lum) {
        $(".wp-block-button").addClass("is-style-outline");
    }
};
*/

// Navbar color.
wp.customize("nav_color", (value) => {
    /*
    value.bind((to) => {
        $("nav").css("background-color", to);
    });
    */
    value.bind((to) => {
        $("#zthemename-inline-css").html((index, currentcontent) => {
            return currentcontent.replace(/(--global--color-nav:\s)#[\d\w]+/, `$1${to}`);
        });
        //getButtonColor(to, wp.customize.get().accent_color);
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
        //
        //getButtonColor(wp.customize.get().nav_color, to);
    });
});

// Navbar color.
wp.customize("nav_btn_type", (value) => {
    value.bind((to) => {
        // remove outline class
        $(".wp-block-button").removeClass(to);
        if (to) {
            $(".wp-block-button").addClass(to);
        }
    });
});
