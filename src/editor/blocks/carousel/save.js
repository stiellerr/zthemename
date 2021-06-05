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
                    <div
                        className="carousel slide"
                        data-bs-ride="carousel"
                        data-bs-interval="5000"
                        data-bs-pause="false"
                    >
                        <div className="carousel-inner">
                            {images.map((image, i) => {
                                const zClassName = `carousel-item${0 === i ? " active" : ""}`;
                                return (
                                    <div key={i} className={zClassName}>
                                        <div className="col-md-3">
                                            <img src={image.url} alt={image.alt || null} />
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
