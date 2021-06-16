import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { TextControl, TextareaControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { compose } from "@wordpress/compose";

import "./sidebar.scss";

let PluginMetaFields = ({ onMetaChange, meta }) => {
    const metaNew = { ...meta };

    return (
        <>
            <TextControl
                value={meta.title}
                label={__("Page Title", "zthemename")}
                onChange={(value) => {
                    metaNew.title = value;
                    onMetaChange(metaNew);
                }}
            />
            <p>
                Max 70 characters, <span className="zthemename-seo-count">{meta.title.length}</span>
            </p>
            <TextareaControl
                value={meta.description}
                label={__("Page Description", "zthemename")}
                onChange={(value) => {
                    metaNew.description = value;
                    onMetaChange(metaNew);
                }}
            />
            <p>
                Max 144 characters,{" "}
                <span className="zthemename-seo-count">{meta.description.length}</span>
            </p>
            {meta.title.length > 0 && <span className="zthemename-seo-title">{meta.title}</span>}
            {meta.description.length > 0 && (
                <p className="zthemename-seo-description">{meta.description}</p>
            )}
        </>
    );
};

PluginMetaFields = compose([
    withSelect((select) => {
        return {
            meta: select("core/editor").getEditedPostAttribute("meta")["_zthemename_post_meta"]
        };
    }),
    withDispatch((dispatch) => {
        return {
            onMetaChange: (metaNew) => {
                dispatch("core/editor").editPost({
                    meta: { _zthemename_post_meta: metaNew }
                });
            }
        };
    })
])(PluginMetaFields);

registerPlugin("zthemename-sidebar", {
    icon: null,
    render: () => {
        return (
            <>
                <PluginDocumentSettingPanel title={__("SEO", "zthemename")}>
                    <PluginMetaFields />
                </PluginDocumentSettingPanel>
            </>
        );
    }
});
