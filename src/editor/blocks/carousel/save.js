/**
 * WordPress dependencies
 */
import { RichText, useBlockProps } from "@wordpress/block-editor";

/**
 * Internal dependencies
 */
import { defaultColumnsNumber } from "./shared";
import { LINK_DESTINATION_ATTACHMENT, LINK_DESTINATION_MEDIA } from "./constants";

export default function save({ attributes }) {
    const {
        images,
        columns = defaultColumnsNumber(attributes),
        imageCrop,
        caption,
        linkTo
    } = attributes;
    const className = `columns-${columns} ${imageCrop ? "is-cropped" : ""}`;
    console.log(images);

    return (
        <>
            {images.length && (
                <div className="wp-block-columns">
                    <div className="carousel slide" data-bs-ride="carousel">
                        <div className="carousel-inner">
                            {images.map((image, i) => {
                                return (
                                    <div key={image.id || image.url} className="carousel-item">
                                        <div className="col-md-3">
                                            <img src={image.url} />
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}
