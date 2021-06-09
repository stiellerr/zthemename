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

    return imageProps;
};
