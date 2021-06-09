/**
 * External dependencies
 */
import { every, filter, find, forEach, map, toString } from "lodash";

/**
 * WordPress dependencies
 */
import { compose } from "@wordpress/compose";
import { withNotices } from "@wordpress/components";
import { MediaPlaceholder, useBlockProps } from "@wordpress/block-editor";
import { Platform, useEffect, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { getBlobByURL, isBlobURL, revokeBlobURL } from "@wordpress/blob";
import { withSelect } from "@wordpress/data";
import { withViewportMatch } from "@wordpress/viewport";
import { View } from "@wordpress/primitives";

/**
 * Internal dependencies
 */
import { sharedIcon } from "./shared-icon";
import { pickRelevantMediaFiles } from "./shared";
import Carousel from "./carousel";

const ALLOWED_MEDIA_TYPES = ["image"];

const PLACEHOLDER_TEXT = Platform.select({
    web: __("Drag images, upload new ones or select files from your library."),
    native: __("ADD MEDIA")
});

function CarouselEdit(props) {
    const { attributes, isSelected, noticeUI, noticeOperations, mediaUpload, onFocus } = props;
    const { images, sizeSlug } = attributes;
    const [selectedImage, setSelectedImage] = useState();
    const [attachmentCaptions, setAttachmentCaptions] = useState();

    function setAttributes(newAttrs) {
        if (newAttrs.ids) {
            throw new Error(
                'The "ids" attribute should not be changed directly. It is managed automatically when "images" attribute changes'
            );
        }

        if (newAttrs.images) {
            newAttrs = {
                ...newAttrs,
                // Unlike images[ n ].id which is a string, always ensure the
                // ids array contains numbers as per its attribute type.
                ids: map(newAttrs.images, ({ id }) => parseInt(id, 10))
            };
        }

        props.setAttributes(newAttrs);
    }

    function onSelectImage(index) {
        return () => {
            setSelectedImage(index);
        };
    }

    function onDeselectImage() {
        return () => {
            setSelectedImage();
        };
    }

    function onMove(oldIndex, newIndex) {
        const newImages = [...images];
        newImages.splice(newIndex, 1, images[oldIndex]);
        newImages.splice(oldIndex, 1, images[newIndex]);
        setSelectedImage(newIndex);
        setAttributes({ images: newImages });
    }

    function onMoveForward(oldIndex) {
        return () => {
            if (oldIndex === images.length - 1) {
                return;
            }
            onMove(oldIndex, oldIndex + 1);
        };
    }

    function onMoveBackward(oldIndex) {
        return () => {
            if (oldIndex === 0) {
                return;
            }
            onMove(oldIndex, oldIndex - 1);
        };
    }

    function onRemoveImage(index) {
        return () => {
            const newImages = filter(images, (img, i) => index !== i);
            setSelectedImage();
            setAttributes({
                images: newImages
            });
        };
    }

    function selectCaption(newImage) {
        // The image id in both the images and attachmentCaptions arrays is a
        // string, so ensure comparison works correctly by converting the
        // newImage.id to a string.
        const newImageId = toString(newImage.id);
        const currentImage = find(images, { id: newImageId });
        const currentImageCaption = currentImage ? currentImage.caption : newImage.caption;

        if (!attachmentCaptions) {
            return currentImageCaption;
        }

        const attachment = find(attachmentCaptions, {
            id: newImageId
        });

        // if the attachment caption is updated
        if (attachment && attachment.caption !== newImage.caption) {
            return newImage.caption;
        }

        return currentImageCaption;
    }

    function onSelectImages(newImages) {
        setAttachmentCaptions(
            newImages.map((newImage) => ({
                // Store the attachmentCaption id as a string for consistency
                // with the type of the id in the images attribute.
                id: toString(newImage.id),
                caption: newImage.caption
            }))
        );
        setAttributes({
            images: newImages.map((newImage) => ({
                ...pickRelevantMediaFiles(newImage, sizeSlug),
                caption: selectCaption(newImage, images, attachmentCaptions),
                // The id value is stored in a data attribute, so when the
                // block is parsed it's converted to a string. Converting
                // to a string here ensures it's type is consistent.
                id: toString(newImage.id)
            }))
        });
    }

    function onUploadError(message) {
        noticeOperations.removeAllNotices();
        noticeOperations.createErrorNotice(message);
    }

    function onFocusGalleryCaption() {
        setSelectedImage();
    }

    function setImageAttributes(index, newAttributes) {
        if (!images[index]) {
            return;
        }

        setAttributes({
            images: [
                ...images.slice(0, index),
                {
                    ...images[index],
                    ...newAttributes
                },
                ...images.slice(index + 1)
            ]
        });
    }

    useEffect(() => {
        if (
            Platform.OS === "web" &&
            images &&
            images.length > 0 &&
            every(images, ({ url }) => isBlobURL(url))
        ) {
            const filesList = map(images, ({ url }) => getBlobByURL(url));
            forEach(images, ({ url }) => revokeBlobURL(url));
            mediaUpload({
                filesList,
                onFileChange: onSelectImages,
                allowedTypes: ["image"]
            });
        }
    }, []);

    useEffect(() => {
        // Deselect images when deselecting the block
        if (!isSelected) {
            setSelectedImage();
        }
    }, [isSelected]);

    const hasImages = !!images.length;
    const hasImageIds = hasImages && images.some((image) => !!image.id);

    const mediaPlaceholder = (
        <MediaPlaceholder
            addToGallery={hasImageIds}
            isAppender={hasImages}
            disableMediaButtons={hasImages && !isSelected}
            icon={!hasImages && sharedIcon}
            labels={{
                title: !hasImages && __("Carousel"),
                instructions: !hasImages && PLACEHOLDER_TEXT
            }}
            onSelect={onSelectImages}
            accept="image/*"
            allowedTypes={ALLOWED_MEDIA_TYPES}
            multiple
            value={hasImageIds ? images : {}}
            onError={onUploadError}
            notices={hasImages ? undefined : noticeUI}
            onFocus={onFocus}
        />
    );

    const blockProps = useBlockProps();

    if (!hasImages) {
        return <View {...blockProps}>{mediaPlaceholder}</View>;
    }

    return (
        <>
            {noticeUI}
            <Carousel
                {...props}
                selectedImage={selectedImage}
                mediaPlaceholder={mediaPlaceholder}
                onMoveBackward={onMoveBackward}
                onMoveForward={onMoveForward}
                onRemoveImage={onRemoveImage}
                onSelectImage={onSelectImage}
                onDeselectImage={onDeselectImage}
                onSetImageAttributes={setImageAttributes}
                onFocusGalleryCaption={onFocusGalleryCaption}
                blockProps={blockProps}
            />
        </>
    );
}

export default compose([
    withSelect((select) => {
        const { getSettings } = select("core/block-editor");
        const { mediaUpload } = getSettings();

        return {
            mediaUpload
        };
    }),
    withNotices,
    withViewportMatch({ isNarrow: "< small" })
])(CarouselEdit);
