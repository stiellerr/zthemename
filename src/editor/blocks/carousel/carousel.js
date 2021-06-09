/**
 * WordPress dependencies
 */
import { __, sprintf } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import CarouselImage from "./carousel-image";

export const Carousel = (props) => {
    const {
        attributes,
        isSelected,
        selectedImage,
        mediaPlaceholder,
        onMoveBackward,
        onMoveForward,
        onRemoveImage,
        onSelectImage,
        onDeselectImage,
        onSetImageAttributes,
        blockProps
    } = props;

    const { images } = attributes;

    return (
        <figure {...blockProps}>
            <ul className="blocks-carousel-grid">
                {images.map((img, index) => {
                    const ariaLabel = sprintf(
                        /* translators: 1: the order number of the image. 2: the total number of images. */
                        __("image %1$d of %2$d in carousel"),
                        index + 1,
                        images.length
                    );

                    return (
                        <li className="blocks-carousel-item" key={img.id || img.url}>
                            <CarouselImage
                                url={img.url}
                                alt={img.alt}
                                id={img.id}
                                isFirstItem={index === 0}
                                isLastItem={index + 1 === images.length}
                                isSelected={isSelected && selectedImage === index}
                                onMoveBackward={onMoveBackward(index)}
                                onMoveForward={onMoveForward(index)}
                                onRemove={onRemoveImage(index)}
                                onSelect={onSelectImage(index)}
                                onDeselect={onDeselectImage(index)}
                                setAttributes={(attrs) => onSetImageAttributes(index, attrs)}
                                caption={img.caption}
                                aria-label={ariaLabel}
                                sizeSlug={attributes.sizeSlug}
                            />
                        </li>
                    );
                })}
            </ul>
            {mediaPlaceholder}
        </figure>
    );
};

export default Carousel;
