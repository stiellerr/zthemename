import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

//import "bootstrap";

onDOMContentLoaded(() => {
    let carousels = SelectorEngine.find(".carousel.slide");

    carousels.forEach((ca) => {
        let items = SelectorEngine.find(".carousel-item", ca);

        items.forEach((el) => {
            // number of slides per carousel-item
            //const minPerSlide = 4;
            const minPerSlide = items.length > 3 ? 4 : items.length;
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
    });
    /*
    let items = SelectorEngine.find(".carousel .carousel-item");

    console.log( items );

    items.forEach((el) => {
        // number of slides per carousel-item
        const minPerSlide = 2;
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
    */
});
