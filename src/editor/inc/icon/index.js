/**
 * External dependencies
 */
import { RichTextShortcut, RichTextToolbarButton } from "@wordpress/block-editor";
import { toggleFormat, registerFormatType, insert, create } from "@wordpress/rich-text";
import { Popover } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { getRectangleFromRange } from "@wordpress/dom";
import { SVG, Path } from "@wordpress/primitives";

/**
 * Internal dependencies
 */
import IconPicker from "../icon-picker";

const customChar = (
    <SVG xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24">
        <Path d="M10 5.4c1.27 0 2.24.36 2.91 1.08c.66.71 1 1.76 1 3.13c0 1.28-.23 2.37-.69 3.27c-.47.89-1.27 1.52-2.22 2.12v2h6v-2h-3.69c.92-.64 1.62-1.34 2.12-2.34c.49-1.01.74-2.13.74-3.35c0-1.78-.55-3.19-1.65-4.22S11.92 3.54 10 3.54s-3.43.53-4.52 1.57c-1.1 1.04-1.65 2.44-1.65 4.2c0 1.21.24 2.31.73 3.33c.48 1.01 1.19 1.71 2.1 2.36H3v2h6v-2c-.98-.64-1.8-1.28-2.24-2.17c-.45-.89-.67-1.96-.67-3.22c0-1.37.33-2.41 1-3.13C7.75 5.76 8.72 5.4 10 5.4z" />
    </SVG>
);

let anchorRange;

registerFormatType("zthemename/icon", {
    title: __("Icon", "zthemename"),
    tagName: "inserticon",
    className: null,
    active: false,
    // edit.
    edit({ isActive, value, onChange }) {
        const onToggle = () => {
            // Set up the anchorRange when the Popover is opened.
            const selection = window.getSelection();
            anchorRange = selection.rangeCount > 0 ? selection.getRangeAt(0) : null;
            onChange(
                toggleFormat(value, {
                    type: "zthemename/icon"
                })
            );
        };

        // Pin the Popover to the caret position.
        const anchorRect = () => {
            return getRectangleFromRange(anchorRange);
        };

        if (isActive) {
            return (
                <Popover
                    position="center"
                    onClick={() => {}}
                    getAnchorRect={anchorRect}
                    expandOnMobile={true}
                    headerTitle={__("Insert Icon", "zthemename")}
                    onClose={() => {
                        onChange(toggleFormat(value, { type: "zthemename/icon" }));
                    }}
                >
                    <IconPicker
                        onChange={({ iconClass, iconContent }) => {
                            let html = `<i class="${iconClass}" data-content="${iconContent}">&#x200b;</i>&#x200b;`;
                            onChange(
                                insert(
                                    value,
                                    create({
                                        html
                                    })
                                )
                            );
                        }}
                    ></IconPicker>
                </Popover>
            );
        }

        return (
            <>
                <RichTextShortcut type="primary" character="i" onUse={onToggle} />
                <RichTextToolbarButton
                    title="Insert icon"
                    onClick={onToggle}
                    isActive={isActive}
                    shortcutType="primary"
                    shortcutCharacter="i"
                    icon={customChar}
                />
            </>
        );
    }
});
