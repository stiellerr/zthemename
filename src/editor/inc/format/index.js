/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";
import { compose, ifCondition } from "@wordpress/compose";
import { withSelect, withDispatch, select, subscribe } from "@wordpress/data";
import { RichTextToolbarButton } from "@wordpress/block-editor";
import { toggleFormat, registerFormatType, unregisterFormatType } from "@wordpress/rich-text";
import { get } from "lodash";

// styles
import "./style.scss";

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
                <RichTextToolbarButton
                    icon="editor-underline"
                    title={settings.title}
                    onClick={onToggle}
                    isActive={isActive}
                />
            );
        }
    });
});

const RichTextJustifyButton = ({ blockId, isBlockJustified, updateBlockAttributes }) => {
    const onToggle = () =>
        updateBlockAttributes(blockId, { align: isBlockJustified ? null : "justify" });

    return (
        <RichTextToolbarButton
            icon="editor-justify"
            title={__("Justify", "zthemename")}
            onClick={onToggle}
            isActive={isBlockJustified}
        />
    );
};

const ConnectedRichTextJustifyButton = compose(
    withSelect((wpSelect) => {
        const selectedBlock = wpSelect("core/block-editor").getSelectedBlock();
        if (!selectedBlock) {
            return {};
        }
        return {
            blockId: selectedBlock.clientId,
            blockName: selectedBlock.name,
            isBlockJustified: "justify" === get(selectedBlock, "attributes.align")
        };
    }),
    withDispatch((dispatch) => ({
        updateBlockAttributes: dispatch("core/block-editor").updateBlockAttributes
    })),
    ifCondition((props) => "core/paragraph" === props.blockName)
)(RichTextJustifyButton);

registerFormatType("zthemename/justify", {
    title: __("Justify", "zthemename"),
    tagName: "p",
    className: null,
    edit: ConnectedRichTextJustifyButton
});
