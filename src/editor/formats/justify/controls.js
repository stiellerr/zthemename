/**
 * External dependencies
 */
import { get } from "lodash";

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { compose, ifCondition } from "@wordpress/compose";
import { RichTextToolbarButton } from "@wordpress/block-editor";
import { withSelect, withDispatch } from "@wordpress/data";
import { alignJustify as icon } from "@wordpress/icons";

class JustifyControl extends Component {
    render() {
        const { blockId, isBlockJustified, updateBlockAttributes } = this.props;

        const onToggle = () => {
            updateBlockAttributes(blockId, { align: isBlockJustified ? null : "justify" });
        };
        return (
            <RichTextToolbarButton
                icon={icon}
                title={__("Justify", "zthemename")}
                onClick={onToggle}
                isActive={isBlockJustified}
            />
        );
    }
}

export default compose(
    withSelect((select) => {
        const selectedBlock = select("core/block-editor").getSelectedBlock();
        if (!selectedBlock) {
            return false;
        }
        return {
            blockId: selectedBlock.clientId,
            blockName: selectedBlock.name,
            isBlockJustified: "justify" === get(selectedBlock, "attributes.align"),
            formatTypes: select("core/rich-text").getFormatTypes()
        };
    }),
    withDispatch((dispatch) => ({
        updateBlockAttributes: dispatch("core/block-editor").updateBlockAttributes
    })),
    ifCondition((props) => {
        if (!props.blockId) {
            return false;
        }
        const checkFormats = props.formatTypes.filter(
            (formats) => formats.name === "wpcom/justify"
        );
        return "core/paragraph" === props.blockName && checkFormats.length === 0;
    })
)(JustifyControl);
