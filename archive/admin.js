/* global ajaxurl */

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

    $(".appearance_page_zthemename-options").on("click", "#sync_places", ({ currentTarget }) => {
        let self = $(currentTarget);
        //let val = self.val();
        self.prop("disabled", true);

        const data = {
            action: "sync_data",
            _wpnonce: $("input[name='_wpnonce']").val(),
            _wp_http_referer: $("input[name='_wp_http_referer']").val()
        };

        $.ajax({
            url: ajaxurl,
            type: "post",
            dataType: "JSON", // Set this so we don't need to decode the response...
            data,
            beforeSend: () => {
                $(".notice").remove();
            },
            success: (response) => {
                if (true === response.success) {
                    if ("OK" === response.data.status) {
                        const place_data = response.data.result;
                        //console.log(place_data);

                        //extractAddress(place_data.address_components);
                        //parseHours(place_data.opening_hours.periods);
                        //parseGeometry(place_data.geometry);

                        //$("input[name$='[url]']").val(place_data.url);
                        //$("input[name$='[phone]']").val(place_data.formatted_phone_number);
                        //$("input[name='blogname']").val(place_data.name);
                        //$("input[name='admin_email']").val(place_data.name);
                    }
                }
            },
            complete: (r) => {
                self.prop("disabled", false);
                // remove any alerts that exist...

                //console.log(r);
                //self.find(".button").prop("disabled", false);
                //$(evt.currentTarget).prop("disabled", false);
                //                        self.prepend(
                //   "<div class='alert alert-success'>" + response.data + "</div>"
                // );
            },
            error: ({ responseJSON }) => {
                //console.log(responseJSON);

                $("h1").append('<div class="notice notice-error"><p>Snychronise failed!</p></div>');
            }
        });
    });
});
