/**
 * WordPress dependencies
 */
import { select, subscribe } from "@wordpress/data";
import { RichTextShortcut, RichTextToolbarButton } from "@wordpress/block-editor";
import { toggleFormat, registerFormatType, unregisterFormatType } from "@wordpress/rich-text";
import { SVG, Path } from "@wordpress/primitives";

const underline = (
    <SVG xmlns="http://www.w3.org/2000/svg" viewBox="-3 -3 24 24">
        <Path d="M14 5h-2v5.71c0 1.99-1.12 2.98-2.45 2.98c-1.32 0-2.55-1-2.55-2.96V5H5v5.87c0 1.91 1 4.54 4.48 4.54c3.49 0 4.52-2.58 4.52-4.5V5zm0 13v-2H5v2h9z" />
    </SVG>
);

const unsubscribe = subscribe(() => {
    const underlineFormat = select("core/rich-text").getFormatType("core/underline");
    if (!underlineFormat) {
        return;
    }
    unsubscribe();
    const settings = unregisterFormatType("core/underline");
    registerFormatType("zthemename/underline", {
        ...settings,
        name: "zthemename/underline",
        edit({ isActive, value, onChange }) {
            const onToggle = () =>
                onChange(
                    toggleFormat(value, {
                        type: "zthemename/underline",
                        attributes: {
                            style: "text-decoration: underline;"
                        }
                    })
                );

            return (
                <>
                    <RichTextShortcut type="primary" character="u" onUse={onToggle} />
                    <RichTextToolbarButton
                        icon={underline}
                        title={settings.title}
                        onClick={onToggle}
                        isActive={isActive}
                        shortcutType="primary"
                        shortcutCharacter="u"
                    />
                </>
            );
        }
    });
});
