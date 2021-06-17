/**
 * WordPress dependencies
 */
import { registerPlugin } from "@wordpress/plugins";
import domReady from "@wordpress/dom-ready";

/**
 * Internal dependencies
 */
import { seo } from "./seo";

const zthemenameRegisterPlugins = () => {
    [seo].forEach(({ name, ...settings }) => {
        if (name) {
            registerPlugin(name, settings);
        }
    });
};

domReady(() => {
    zthemenameRegisterPlugins();
});
