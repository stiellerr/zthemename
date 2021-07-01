/* global ga */

import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

onDOMContentLoaded(() => {
    // bail early if ga is undefined
    if ("undefined" === typeof ga) {
        return;
    }

    // grab all the telephone and email clicks.
    SelectorEngine.find('a[href^="tel:"],a[href^="mailto:"]').forEach((href) => {
        href.addEventListener("click", (props) => {
            const { target } = props;
            ["header", "main", "aside", "footer"].every((location) => {
                if (target.closest(location)) {
                    const data = target.href.split(":");
                    // send click event to google analytics.
                    ga("send", "event", data[0], data[1], location);
                    return false;
                }
                return true;
            });
        });
    });
});
