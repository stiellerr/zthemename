/**
 * Internal dependencies
 */
import IconControl from "./controls";

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
const name = "zthemename/icon";

export const icon = {
    name,
    title: __("Icon", "zthemename"),
    tagName: "zthemenameicon",
    className: null,
    active: false,
    edit({ isActive, value, onChange }) {
        return (
            <>
                <IconControl name={name} isActive={isActive} value={value} onChange={onChange} />
            </>
        );
    }
};
