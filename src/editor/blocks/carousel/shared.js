/**
 * External dependencies
 */
import { get, pick } from "lodash";

export const pickRelevantMediaFiles = (image, sizeSlug = "small") => {
    const imageProps = pick(image, ["alt", "id", "caption"]);
    imageProps.url =
        get(image, ["sizes", sizeSlug, "url"]) ||
        get(image, ["media_details", "sizes", sizeSlug, "source_url"]) ||
        image.url;
    imageProps.width =
        get(image, ["sizes", sizeSlug, "width"]) ||
        get(image, ["sizes", "full", "width"]) ||
        get(image, ["media_details", "sizes", sizeSlug, "width"]) ||
        get(image, ["media_details", "width"]);
    imageProps.height =
        get(image, ["sizes", sizeSlug, "height"]) ||
        get(image, ["sizes", "full", "height"]) ||
        get(image, ["media_details", "sizes", sizeSlug, "height"]) ||
        get(image, ["media_details", "height"]);

    return imageProps;
};
