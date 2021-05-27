//import "bootstrap/scss/bootstrap.scss";
import "./index.scss";

//import "bootstrap";
import "bootstrap/js/src/collapse";
import "bootstrap/js/src/dropdown";

import "bootstrap/js/src/carousel";

import "./js/fa-loader.js";
import "./js/mailer.js";

let items = document.querySelectorAll(".carousel .carousel-item");

items.forEach((el) => {
    // number of slides per carousel-item
    const minPerSlide = 4;
    let next = el.nextElementSibling;
    for (var i = 1; i < minPerSlide; i++) {
        if (!next) {
            // wrap carousel by using first child
            next = items[0];
        }
        let cloneChild = next.cloneNode(true);
        el.appendChild(cloneChild.children[0]);
        next = next.nextElementSibling;
    }
});
