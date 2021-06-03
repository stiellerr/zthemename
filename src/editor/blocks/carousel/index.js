/**
 * WordPress dependencies
 */
import { __, _x } from "@wordpress/i18n";
import { gallery } from "@wordpress/icons";

/**
 * Internal dependencies
 */
//import deprecated from './deprecated';
import edit from "./edit";
import metadata from "./block.json";
import save from "./save";
//import transforms from './transforms';

//import './style.scss';

const icon = {
    foreground: "#007bff",
    src: gallery
};

const { name } = metadata;

export { metadata, name };

export const settings = {
    title: _x("Carousel", "block title"),
    description: __("Display multiple images in a carousel."),
    icon,
    keywords: [__("carousel"), __("slider")],
    /*
	 example: {
		 attributes: {
			 columns: 2,
			 images: [
				 {
					 url:
						 'https://s.w.org/images/core/5.3/Glacial_lakes%2C_Bhutan.jpg',
				 },
				 {
					 url:
						 'https://s.w.org/images/core/5.3/Sediment_off_the_Yucatan_Peninsula.jpg',
				 },
			 ],
		 },
	 },
	 transforms,
	 */
    edit,
    /*
	 edit: () => {

        return (
            <>
                <h1>Hello World!</h1>
            </>
        );
    },
    save: (props) => {
        console.log("save");
        console.log(props);
        return (
            <>
                <h1>Hello World!</h1>
            </>
        );
    }
    */
    save
    //deprecated,
};
