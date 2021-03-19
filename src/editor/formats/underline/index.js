/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { toggleFormat } from "@wordpress/rich-text";
import { RichTextToolbarButton, RichTextShortcut } from "@wordpress/block-editor";
import { select } from "@wordpress/data";
import { formatUnderline as icon } from "@wordpress/icons";

/**
 * Block constants
 */
const name = "zthemename/underline";

export const underline = {
    name,
    title: __("Underline", "zthemename"),
    tagName: "span",
    className: null,
    attributes: {
        style: "style"
    },
    edit({ isActive, value, onChange }) {
        const formatTypes = select("core/rich-text").getFormatTypes();
        const checkFormats = formatTypes.filter((formats) => formats.name === "wpcom/underline");

        const onToggle = () => {
            onChange(
                toggleFormat(value, {
                    type: name,
                    attributes: {
                        style: "text-decoration: underline;"
                    }
                })
            );
        };

        return (
            <>
                <RichTextShortcut type="primary" character="u" onUse={onToggle} />
                {checkFormats.length === 0 && (
                    <RichTextToolbarButton
                        icon={icon}
                        title={__("Underline", "zthemename")}
                        onClick={onToggle}
                        isActive={isActive}
                        shortcutType="primary"
                        shortcutCharacter="u"
                    />
                )}
            </>
        );
    }
};
