/**
 * WordPress dependencies
 */
import { RichText, useBlockProps } from "@wordpress/block-editor";

/**
 * Internal dependencies
 */

export default function save({ attributes }) {
    const { images } = attributes;

    return (
        <>
            {images.length && (
                <div {...useBlockProps.save({ className: "wp-block-columns" })}>
                    <div
                        className="carousel slide"
                        data-bs-ride="carousel"
                        data-bs-interval="1500"
                        data-bs-pause="false"
                        data-bs-keyboard="false"
                    >
                        <div className="carousel-inner">
                            {images.map((image, i) => {
                                const className = `carousel-item${0 === i ? " active" : ""}`;
                                return (
                                    <div className={className} key={image.id || image.url}>
                                        <figure>
                                            <div>
                                                <img
                                                    src={image.url}
                                                    alt={image.alt || null}
                                                    width={image.width || null}
                                                    height={image.height || null}
                                                    data-id={image.id}
                                                />
                                            </div>
                                            {!RichText.isEmpty(image.caption) && (
                                                <RichText.Content
                                                    tagName="figcaption"
                                                    className="blocks-carousel-item__caption"
                                                    value={image.caption}
                                                />
                                            )}
                                        </figure>
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
