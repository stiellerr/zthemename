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
                if (self.next("input")) {
                    self.next("input").attr("value", null);
                    //self.next("input").val(null);
                }
                if (self.prev("input")) {
                    //self.attr("value", null);
                    //self.prev("input").val(val).attr("value", val);
                    //self.attr("value", null);
                    //self.prev("input").val(val).attr("value", val);
                }
            }

            self.attr("value", val);
            //
            //self.val(val).attr("value", val);
        }
    );
});
