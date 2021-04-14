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
        "div[id*=zthemename_business_hours].widget input:first-child",
        ({ currentTarget }) => {
            //let self = $(currentTarget);
            // self.attr("value", self.val());
            //console.log("first-child");
            //self.parent().next().first().css("");
            //self.parent().removeClass("single");
            //if (!self.val() || "Closed" === self.val() || "24 hours" === self.val()) {
            //self.parent().addClass("single");
            //.next().first().css("display", "none");
            //}
            //$(currentTarget).parent().next().first().css("background-color", "red");
            // do something
            //console.log($(currentTarget).val());
        }
    );
    $("body").on(
        "change",
        "div[id*=zthemename_business_hours].widget div > input",
        ({ currentTarget }) => {
            let self = $(currentTarget);
            let val = self.val();

            self.attr("value", val);

            //self.next("input").css("background-color", "red");

            if (!val || "Closed" === val || "24 Hours" === val) {
                if (self.next("input")) {
                    self.next("input").val("11:11");
                }
            }

            //console.log(self.val());
            //self.parent().next().first().css("");

            //self.parent().removeClass("single");

            //if (!self.val() || "Closed" === self.val() || "24 hours" === self.val()) {
            //self.parent().addClass("single");
            //.next().first().css("display", "none");
            //}
            //$(currentTarget).parent().next().first().css("background-color", "red");
            // do something
            //console.log($(currentTarget).val());
        }
    );
});
