import "./index.scss";

import "bootstrap";
//import "bootstrap/dist/css/bootstrap.min.css";
//import "../node_modules/bootstrap/scss/utilities";

//import "@fortawesome/fontawesome-free/metadata/icons.yml";

//import bootstrap from "bootstrap";
//import 'bootstrap/dist/css/bootstrap.min.css';

//let inline_css = document.getElementById("zthemename-inline-css");

document.addEventListener("DOMContentLoaded", () => {
    const icons = document.querySelectorAll("i[data-content]");

    let css = "";

    Array.prototype.forEach.call(icons, (icon) => {
        const content = icon.dataset.content;
        if (css.indexOf(content) !== -1) {
            return;
        }
        const match = icon.className.match(/fa(-[a-z]+)+/)[0];
        css += `.${match}:before { content: '\\${content}'; }`;
    });

    if (css) {
        let style = document.createElement("style");
        style.type = "text/css";
        document.getElementsByTagName("head")[0].appendChild(style);
        style.appendChild(document.createTextNode(css));
    }
});

console.log("front end loaded");
//alert("HI");
