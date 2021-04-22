/* global ajaxurl */

import "./admin.scss";

console.log("admin loaded");

/**
 * External dependencies
 */
import $ from "jquery";

$(document).on("ready", () => {
    $(".appearance_page_zthemename-options")
        .on("click", "#download_data", ({ currentTarget }) => {
            const conf = confirm(
                "Are you sure ??\nClicking OK will overwrite all existing place data, across your site."
            );

            if (false === conf) {
                return;
            }

            let self = $(currentTarget);

            const data = {
                action: "download_data",
                _wpnonce: $("input[name='_wpnonce']").val(),
                _wp_http_referer: $("input[name='_wp_http_referer']").val()
            };

            $.ajax({
                url: ajaxurl,
                type: "post",
                dataType: "JSON", // Set this so we don't need to decode the response...
                data,
                beforeSend: () => {
                    self.prop("disabled", true);
                    $(".wrap > .notice").remove();
                },
                success: (response) => {
                    const { success } = response;
                    const message = success ? "Download Successful!" : "zzz to do...";

                    $(".wrap > form").before(
                        `<div class="notice notice-success"><p>${message}</p></div>`
                    );
                },
                complete: () => {
                    self.prop("disabled", false);
                },
                error: (response) => {
                    const { status, statusText } = response;
                    $(".wrap > form").before(
                        `<div class="notice notice-error"><p>Error ${status}: ${statusText}</p></div>`
                    );
                }
            });
        })
        //
        .on("input", "#zthemename_options\\[key\\],#zthemename_options\\[place_id\\]", () => {
            $("#download_data").prop("disabled", true);
        });
});
