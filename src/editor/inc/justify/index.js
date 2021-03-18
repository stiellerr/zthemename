/**
 * Internal dependencies
 */
import JustifyControl from "./controls";

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerFormatType } from "@wordpress/rich-text";
import { domReady } from "@wordpress/dom-ready";

/**
 * Block constants
 */
/*
const name = "zthemename/justify";

export const justify = {
    name,
    title: __("Align text justify", "zthemename"),
    tagName: "p",
    className: null,
    attributes: {
        style: "style"
    },
    edit({ isActive, value, onChange, activeAttributes }) {
        return (
            <>
                <JustifyControl
                    name={name}
                    isActive={isActive}
                    value={value}
                    onChange={onChange}
                    activeAttributes={activeAttributes}
                />
            </>
        );
    }
};
*/
domReady(
    registerFormatType("zthemename/justify", {
        title: __("Align text justify", "zthemename"),
        tagName: "p",
        className: null,
        attributes: {
            style: "style"
        },
        edit({ isActive, value, onChange, activeAttributes }) {
            return (
                <>
                    <JustifyControl
                        name={name}
                        isActive={isActive}
                        value={value}
                        onChange={onChange}
                        activeAttributes={activeAttributes}
                    />
                </>
            );
        }
    })
);
