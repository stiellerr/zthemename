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
    /*
    <div class="wp-block-columns">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="col-md-3">
                        <img src="http://localhost/wp-content/uploads/2019/02/richard-stieller.png" alt="...">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-3">
                        <img src="http://localhost/wp-content/uploads/2019/01/charley-jean-brown.png" alt="...">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-3">
                        <img src="http://localhost/wp-content/uploads/2019/02/sarah-good.png" alt="...">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-3">
                        <img src="http://localhost/wp-content/uploads/2019/02/john-mcfadgen.png" alt="...">
                    </div>
                </div>
            </div>
        </div>
    </div>
    */

    return (
        
            {images.length && (
                <div className="wp-block-columns">
                    <div className="carousel slide" data-bs-ride="carousel">
                        <div className="carousel-inner">
                            {images.map((image, i) => {
                                return <h3 key={i}>{i}</h3>;
                            })}
                        </div>
                    </div>
                </div>
            )}
            {/*
        <div>

        </div>
        

            <figure {...useBlockProps.save({ className })}>
                <ul className="blocks-gallery-grid">
                    {images.map((image) => {
                        let href;

                        switch (linkTo) {
                            case LINK_DESTINATION_MEDIA:
                                href = image.fullUrl || image.url;
                                break;
                            case LINK_DESTINATION_ATTACHMENT:
                                href = image.link;
                                break;
                        }

                        const img = (
                            <img
                                src={image.url}
                                alt={image.alt}
                                data-id={image.id}
                                data-full-url={image.fullUrl}
                                data-link={image.link}
                                className={image.id ? `wp-image-${image.id}` : null}
                            />
                        );

                        return (
                            <li key={image.id || image.url} className="blocks-gallery-item">
                                <figure>
                                    {href ? <a href={href}>{img}</a> : img}
                                    {!RichText.isEmpty(image.caption) && (
                                        <RichText.Content
                                            tagName="figcaption"
                                            className="blocks-gallery-item__caption"
                                            value={image.caption}
                                        />
                                    )}
                                </figure>
                            </li>
                        );
                    })}
                </ul>
                {!RichText.isEmpty(caption) && (
                    <RichText.Content
                        tagName="figcaption"
                        className="blocks-gallery-caption"
                        value={caption}
                    />
                )}
            </figure>
            */}
    );
}
