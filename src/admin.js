import "./admin.scss";

console.log("admin loaded");

/**
 * External dependencies
 */
import $ from "jquery";

$(document).on("ready", () => {
    console.log("page ready...");

    $(document).on("change", ".zzz_test", ({ currentTarget }) => {
        console.log($(currentTarget).val());
        //console.log(e);
        // do something
        console.log("Hello World!");
    });
    //var zzz = $(".zzz_test");
});
