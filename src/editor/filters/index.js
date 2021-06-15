/**
 * WordPress dependencies
 */
import { addFilter } from "@wordpress/hooks";

const zthemenameModifyButtonDefaults = (settings, name) => {
    // change default border radius of button to 4px
    if (name === "core/button") {
        if (settings.attributes.borderRadius) {
            settings.attributes.borderRadius = {
                type: "number",
                default: 2
            };
        }
    }
    return settings;
};

addFilter(
    "blocks.registerBlockType",
    "zthemename/modify-button-defaults",
    zthemenameModifyButtonDefaults
);
