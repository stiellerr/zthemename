import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

onDOMContentLoaded(() => {
    let css = SelectorEngine.findOne("#zthemename-inline-css");

    css.innerHTML = css.innerHTML.trim();

    SelectorEngine.find("i[data-content]").forEach((icon) => {
        const content = icon.dataset.content;
        if (css.innerHTML.indexOf(content) !== -1) {
            return;
        }
        const match = icon.className.match(/fa(-[a-z]+)+/)[0];
        css.innerHTML += `.${match}:before { content: '\\${content}'; }`;
    });
});
