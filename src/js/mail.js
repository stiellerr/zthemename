import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import EventHandler from "bootstrap/js/src/dom/event-handler";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

const SELECTOR_FORM = "form.needs-validation";
const EVENT_SUBMIT = `submit`;

class Mail {
    constructor(element) {
        if (!element) {
            return;
        }
        this._element = element;
        this._addEventListeners();
    }
    _addEventListeners() {
        EventHandler.on(this._element, EVENT_SUBMIT, (event) => this._submit(event));
    }

    _submit(event) {
        event.preventDefault();

        if (!this._element.checkValidity()) {
            this._element.classList.add("was-validated");
            return;
        }

        let formData = new FormData(event.target);
        const XHR = new XMLHttpRequest();

        // add event listeners..
        XHR.onloadstart = () => this._onloadstart();
        XHR.onload = ({ currentTarget }) => this._onload(currentTarget);
        XHR.onerror = ({ currentTarget }) => this._onerror(currentTarget);
        XHR.onloadend = () => this._onloadend();

        this._btn = SelectorEngine.findOne(".wp-block-button__link", this._element);

        // Set up our request
        XHR.open("POST", "zzz", true);
        XHR.send(formData);
    }

    _onloadstart() {
        this._btn.setAttribute("disabled", true);

        console.log("onloadstart");
    }

    _onload(currentTarget) {
        console.log("onload");
    }

    _onerror(currentTarget) {
        console.log("onerror");
    }

    _onloadend() {
        this._btn.removeAttribute("disabled");
        this._element.reset();
        this._element.classList.remove("was-validated");

        console.log("onloadend");
    }
}

onDOMContentLoaded(() => {
    SelectorEngine.find(SELECTOR_FORM).forEach((form) => new Mail(form));
});

export default Mail;
