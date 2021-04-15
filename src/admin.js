import "./admin.scss";

console.log("admin loaded");

/**
 * External dependencies
 */
import $ from "jquery";

$(document).on("ready", () => {
    // business hours widget.
    $("body").on(
        "change",
        "div[id*=zthemename_business_hours].widget div > input",
        ({ currentTarget }) => {
            let self = $(currentTarget);
            let val = self.val();

            if (!val || "Closed" === val || "24 Hours" === val) {
                if (self.next("input").length) {
                    self.next("input").attr("value", null);
                    self.attr("value", val);
                }
                if (self.prev("input").length) {
                    self.prev("input").attr("value", val);
                    self.val(null);
                }
            } else {
                self.attr("value", val);
            }
        }
    );
});
