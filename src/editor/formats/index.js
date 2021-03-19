/**
 * Internal dependencies
 */
import { justify } from "./justify";
import { underline } from "./underline";
import { icon } from "./icon";

/**
 * WordPress dependencies
 */
import { registerFormatType, unregisterFormatType } from "@wordpress/rich-text";
import { select } from "@wordpress/data";
import domReady from "@wordpress/dom-ready";

const zthemenameRegisterFormats = () => {
    [justify, underline, icon].forEach(({ name, ...settings }) => {
        // unregister core underline if it exists...
        if ("zthemename/underline" === name) {
            const underlineFormat = select("core/rich-text").getFormatType("core/underline");
            if (underlineFormat) {
                unregisterFormatType("core/underline");
            }
        }
        if (name) {
            registerFormatType(name, settings);
        }
    });
};

domReady(() => {
    zthemenameRegisterFormats();
});
