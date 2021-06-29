<?php
/**
 * Zthemename Theme Images
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zthemename
 */

if ( ! class_exists( 'Zthemename_Images' ) ) {

	/**
	 * Custom class used to filter and optimize theme images
	 */
	class Zthemename_Images {

        /**
         * Instantiate the object.
         *
         * @access public
         *
         * @since zthemename 1.0
         */
        public function __construct() {

            add_filter( 'wp_generate_attachment_metadata', array( &$this, 'wp_generate_attachment_metadata' ), 10, 3 );
        }

        // used when new images are created. ie on upload, edit or crop
        function wp_generate_attachment_metadata( $metadata, $attachment_id, $context ) {

            $metadata = $this->zthemename_save_image_filter( $metadata, $attachment_id );
            
            return $metadata;
        }

        // this is where all the magic happens
        function zthemename_save_image_filter( $image_meta, $image_id ) {

            if ( ! isset( $image_meta ) || ! get_post( $image_id ) ) {
                return $image_meta;
            }
            
            // get image name
            $path = get_attached_file( $image_id );

            $basename = pathinfo( $path, PATHINFO_BASENAME );
            $dirname  = pathinfo( $path, PATHINFO_DIRNAME );          

            $image_meta[ 'sizes' ][ 'full' ] = array(
                'file'      => $basename,
                'width'     => $image_meta[ 'width' ],
                'height'    => $image_meta[ 'height' ],
                'mime-type' => get_post_mime_type( $image_id )
            );

            foreach( $image_meta[ 'sizes' ] as $size => &$data ) {

                // these need to be seperate, causes issues otherwise...
                $mimetype = (string) $data[ 'mime-type' ];
                $child    = path_join( $dirname, wp_basename( $data[ 'file' ] ) );

                if ( ! file_exists( $child ) ) {
                    continue;
                }

                if ( 'image/jpeg' == $mimetype || 'image/png' == $mimetype ) {
                    // if image is new, and size is full, open file and compress it. wp doesnt do the original by default...
                    if ( 'full' == $size ) {
                        $editor = wp_get_image_editor( $child );
                        $editor->save( $child, $mimetype );
                        unset( $editor );
                    }

                    if ( class_exists( 'Imagick' ) ) {
                        $imagick = new Imagick( $child );

                        // set interlacing if not set...  
                        if ( imagick::INTERLACE_PLANE !== $imagick->getInterlaceScheme() ) {
                            $imagick->setInterlaceScheme( imagick::INTERLACE_PLANE );
                            $imagick->writeImage();
                        }

                        $imagick->clear();
                        $imagick->destroy();
                        unset( $imagick );
                    }
                }
            }
            unset( $image_meta[ 'sizes' ][ 'full' ] );

            return $image_meta;
        }

	}

    new Zthemename_Images();
}

