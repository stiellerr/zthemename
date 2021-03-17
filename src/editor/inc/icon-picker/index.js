/* global fa_icons */

/**
 * External dependencies
 */
import { Component } from "@wordpress/element";
import { __experimentalInputControl as InputControl } from "@wordpress/components";
import $ from "jquery";
import _ from "underscore";
import "@fortawesome/fontawesome-free/metadata/icons.yml";

// styles
import "./editor.scss";

export default class IconPicker extends Component {
    // globals...
    itemList = {};

    self = null;

    // onChange
    onChange = (value) => {
        //console.log("onChange");

        this.itemList.hide();

        if (value && value.trim()) {
            this.itemList.filter(`[data-query*="${value.trim().toLowerCase()}"]`).show();
        }
    };

    componentDidMount() {
        //console.log("componentDidMount");

        // init icon picker...
        $.getJSON(fa_icons.data, (data) => {
            const icons = JSON.parse(JSON.stringify(data));
            _.each(icons, (data, name) => {
                let arr = [];
                if (data.search.terms.length) {
                    arr = data.search.terms;
                    //console.log(arr);
                }
                arr.push(name);
                _.each(data.styles, (suffix) => {
                    this.self
                        .find(".zthemename-icon-picker__items")
                        .append(
                            `<i class="fa${suffix[0]} fa-${name}" data-content="${
                                data.unicode
                            }" data-query="${arr.join(" ")}"></i>`
                        );
                });
            });
            //console.log("icons");
            //console.log(icons);

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
        //console.log("render");

        return (
            <>
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
                            value={this.props.value ? this.props.value : ""}
                            autoComplete="off"
                        />
                    </div>
                    <div className="zthemename-icon-picker__items"></div>
                </div>
            </>
        );
    };
}
