/**
 * Internal dependencies
 */
import JustifyControl from "./controls";

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";

/**
 * Styles
 */
import "./style.scss";

/**
 * Block constants
 */
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
