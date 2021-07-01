/* global zthemename */

/**
 * External dependencies
 */
import { onDOMContentLoaded } from "bootstrap/js/src/util/index";
import EventHandler from "bootstrap/js/src/dom/event-handler";
import SelectorEngine from "bootstrap/js/src/dom/selector-engine";

/**
 * Constants
 */
const SELECTOR_FORM = "form.needs-validation";
const EVENT_SUBMIT = `submit`;

class Mailer {
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

        // remove alert if it exists
        this._alert = SelectorEngine.findOne(".alert", this._element);
        this._alert && this._alert.remove();

        if (!this._element.checkValidity()) {
            this._element.classList.add("was-validated");
            return;
        }

        this._alert = document.createElement("div");
        this._alert.classList.add("alert");

        let formData = new FormData(event.target);
        formData.append("action", "send_form");
        formData.append("security", zthemename.ajax_nonce);

        const XHR = new XMLHttpRequest();

        // add event listeners..
        XHR.onloadstart = () => this._onloadstart();
        XHR.onload = ({ currentTarget }) => this._onload(currentTarget);
        XHR.onerror = ({ currentTarget }) => this._onerror(currentTarget);
        XHR.onloadend = () => this._onloadend();

        // Set up our request
        XHR.open("POST", zthemename.ajax_url, true);
        XHR.send(formData);
    }

    _onloadstart() {
        SelectorEngine.findOne(".wp-block-button__link", this._element).setAttribute(
            "disabled",
            true
        );
    }

    _onload(currentTarget) {
        if (200 === currentTarget.status) {
            const response = JSON.parse(currentTarget.response);
            this._alert.innerHTML = response.data;
            this._alert.classList.add("alert-success");
        } else {
            this._onerror(currentTarget);
        }
    }

    _onerror(currentTarget) {
        this._alert.innerHTML = `Error: ${currentTarget.status} ${currentTarget.statusText}`;
        this._alert.classList.add("alert-danger");
    }

    _onloadend() {
        SelectorEngine.findOne(".wp-block-button__link", this._element).removeAttribute("disabled");
        this._element.reset();
        this._element.classList.remove("was-validated");
        this._element.prepend(this._alert);
    }
}

onDOMContentLoaded(() => {
    SelectorEngine.find(SELECTOR_FORM).forEach((form) => new Mailer(form));
});

export default Mailer;
