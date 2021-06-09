/**
 * WordPress dependencies
 */
import { __, _x } from "@wordpress/i18n";
import { gallery } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import metadata from "./block.json";
import save from "./save";

const icon = {
    foreground: "#007bff",
    src: gallery
};

const { name } = metadata;

export { metadata, name };

export const settings = {
    title: _x("Carousel", "block title"),
    description: __("Display multiple images in a carousel."),
    icon,
    keywords: [__("carousel"), __("slider")],
    edit,
    save
};
