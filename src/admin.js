/* global ajaxurl */

import "./admin.scss";

console.log("admin loaded");

/**
 * External dependencies
 */
import $ from "jquery";

$(document).on("ready", () => {
    $(".appearance_page_zthemename-options")
        .on("click", "#sync_places", ({ currentTarget }) => {
            let self = $(currentTarget);
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
                        $("h1").append('<div class="notice notice-success"><p>Success!</p></div>');
                    }
                },
                complete: () => {
                    self.prop("disabled", false);
                },
                error: ({ responseJSON }) => {
                    $("h1").append(
                        '<div class="notice notice-error"><p>Snychronise failed!</p></div>'
                    );
                }
            });
        })
        //
        .on("input", "#zthemename_options\\[key\\],#zthemename_options\\[place_id\\]", () => {
            $("#sync_places").prop("disabled", true);
        });
});
