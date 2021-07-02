/* global ga */
import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

//
export const sendEvent = (target, params = {}) => {
    if ("undefined" === typeof ga) {
        return;
    }

    const defaults = {
        hitType: "event",
        eventLabel: ["header", "main", "aside", "footer"].find((location) => {
            if (target.closest(location)) {
                return location;
            }
        })
    };
    params = Object.assign({}, defaults, params);

    ga("send", params);
};

onDOMContentLoaded(() => {
    // grab all the telephone and email clicks and attatch an event listener.
    SelectorEngine.find('a[href^="tel:"],a[href^="mailto:"]').forEach((href) => {
        href.addEventListener("click", (props) => {
            const { target } = props;
            // build params.
            const params = ["eventCategory", "eventAction"].reduce(
                (o, k, i) => ({ ...o, [k]: target.href.split(":")[i] }),
                {}
            );
            // send event.
            sendEvent(target, params);
        });
    });
});
