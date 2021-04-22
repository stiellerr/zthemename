/* global Color */

const MIN_CONTRAST = 4.5;
const AMOUNT = 10;

class Zthemename_Color {
    constructor(accent) {
        if (!accent) {
            return;
        }
        this._accent = new Color(accent);
    }
    init(background) {
        if (!background) {
            return;
        }
        this._background = new Color(background);
        this._accentReadable = this._accent
            .clone()
            .getReadableContrastingColor(this._background, MIN_CONTRAST);
        return this.getColorObject();
    }
    _getAccentLighten() {
        return this._accentReadable.clone().lighten(AMOUNT).toCSS();
    }
    _getAccentDarken() {
        return this._accentReadable.clone().darken(AMOUNT).toCSS();
    }
    _getAccent() {
        return this._accentReadable.toCSS();
    }
    _getAccentHover() {
        let lum = this._accentReadable.toLuminosity();
        let hex = lum >= 0.5 ? this._getAccentDarken() : this._getAccentLighten();

        return hex;
    }
    isOutline() {
        const lum = this._background.getDistanceLuminosityFrom(this._accent);
        return 4.5 > lum ? true : false;
    }
    getColorObject() {
        return {
            accent: this._getAccent(),
            accentHover: this._getAccentHover()
        };
    }
}

export default Zthemename_Color;
