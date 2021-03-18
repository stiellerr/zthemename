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
import { withSelect, withDispatch } from "@wordpress/data";
import { RichTextToolbarButton } from "@wordpress/block-editor";

class JustifyControl extends Component {
    render() {
        const { blockId, isBlockJustified, updateBlockAttributes } = this.props;

        const onToggle = () => {
            updateBlockAttributes(blockId, { align: isBlockJustified ? null : "justify" });
        };
        return (
            <RichTextToolbarButton
                icon="editor-justify"
                title={__("Justify", "zthemename")}
                onClick={onToggle}
                isActive={isBlockJustified}
            />
        );
    }
}

export default compose(
    withSelect((select) => {
        /*
        const isDisabled = select("core/edit-post").isFeatureActive(
            "disableEditorsKitJustifyFormats"
		);
		*/
        const selectedBlock = select("core/block-editor").getSelectedBlock();
        /*
        if (isDisabled || !selectedBlock) {
            return {
                isDisabled
            };
		}
		*/
        if (!selectedBlock) {
            return;
        }
        return {
            //isDisabled,
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
        /*
        if (props.isDisabled || !props.blockId) {
            return false;
		}
		*/
        if (!props.blockId) {
            return false;
        }
        const checkFormats = props.formatTypes.filter(
            (formats) => formats.name === "wpcom/justify"
        );
        return "core/paragraph" === props.blockName && checkFormats.length === 0;
    })
)(JustifyControl);
