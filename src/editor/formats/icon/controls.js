/* global fa_icons */

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { toggleFormat, insert, create } from "@wordpress/rich-text";
import { getRectangleFromRange } from "@wordpress/dom";
import { Popover, __experimentalInputControl as InputControl } from "@wordpress/components";
import { RichTextShortcut, RichTextToolbarButton } from "@wordpress/block-editor";

/**
 * External dependencies
 */
import $ from "jquery";
import { each } from "underscore";

/**
 * Internal dependencies
 */
import "@fortawesome/fontawesome-free/metadata/icons.yml";

class Picker extends Component {
    itemList = {};
    self = null;

    onChange = (value) => {
        this.itemList.hide();

        if (value && value.trim()) {
            this.itemList.filter(`[data-query*="${value.trim().toLowerCase()}"]`).show();
        }
    };

    componentDidMount() {
        // init icon picker...
        $.getJSON(fa_icons.data, (data) => {
            const icons = JSON.parse(JSON.stringify(data));
            each(icons, (data, name) => {
                let arr = [];
                if (data.search.terms.length) {
                    arr = data.search.terms;
                }
                arr.push(name);
                each(data.styles, (suffix) => {
                    this.self
                        .find(".zthemename-icon-picker__items")
                        .append(
                            `<i class="fa${suffix[0]} fa-${name}" data-content="${
                                data.unicode
                            }" data-query="${arr.join(" ")}"></i>`
                        );
                });
            });
            // init item list
            this.itemList = this.self.find(".zthemename-icon-picker__items").children();
            this.itemList.hide();

            // set default value
            if (this.props.value) {
                this.itemList.filter(`[class="${this.props.value}"]`).show();
            }

            // add click event listener
            this.self
                .find(".zthemename-icon-picker__items > i")
                .on("click", ({ currentTarget }) => {
                    let e = $(currentTarget);
                    this.props.onChange({
                        iconClass: e.attr("class"),
                        iconContent: e.data("content")
                    });
                    this.itemList
                        .hide()
                        .filter(`[class="${e.attr("class")}"]`)
                        .show();
                });
        });
    }

    render = () => {
        const { anchorRect, onClose, value } = this.props;

        return (
            <Popover
                position="center"
                onClick={() => {}}
                getAnchorRect={anchorRect}
                expandOnMobile={true}
                headerTitle={__("Insert Icon", "zthemename")}
                onClose={() => {
                    onClose();
                }}
            >
                <div
                    className="zthemename-icon-picker"
                    ref={(node) => {
                        this.self = $(node);
                    }}
                >
                    <div className="zthemename-icon-picker__search">
                        <InputControl
                            onChange={this.onChange}
                            type="search"
                            placeholder="Type to filter"
                            value={value ? value : ""}
                            autoComplete="off"
                        />
                    </div>
                    <div className="zthemename-icon-picker__items"></div>
                </div>
            </Popover>
        );
    };
}

export default class IconControl extends Component {
    anchorRange = null;

    render() {
        const { isActive, onChange, value } = this.props;

        const onToggle = () => {
            // Set up the anchorRange when the Popover is opened.
            const selection = window.getSelection();
            this.anchorRange = selection.rangeCount > 0 ? selection.getRangeAt(0) : null;
            onChange(
                toggleFormat(value, {
                    type: "zthemename/icon"
                })
            );
        };

        // Pin the Popover to the caret position.
        const anchorRect = () => {
            return getRectangleFromRange(this.anchorRange);
        };

        if (isActive) {
            return (
                <Picker
                    anchorRect={anchorRect}
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
                    onClose={() => {
                        onChange(toggleFormat(value, { type: "zthemename/icon" }));
                    }}
                ></Picker>
            );
        }

        return (
            <>
                <RichTextShortcut type="primary" character="e" onUse={onToggle} />
                <RichTextToolbarButton
                    icon={"editor-customchar"}
                    title={__("Insert icon", "zthemename")}
                    onClick={onToggle}
                    isActive={isActive}
                    shortcutType="primary"
                    shortcutCharacter="e"
                />
            </>
        );
    }
}
