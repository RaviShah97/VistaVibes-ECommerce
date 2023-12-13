/**
 * Admin term scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        /**
         * Color picker.
         */
        ( function () {
            $( '.wlpf-term-color-input' ).wpColorPicker();
        } )();

        /**
         * Image uploader.
         */
        ( function () {
            $( '.wlpf-term-image-field' ).each( function () {
                let field = $( this ),
                    title = field.attr( 'data-wlpt-title' ),
                    btnTxt = field.attr( 'data-wlpt-button-text' ),
                    imgTag = field.find( '.wlpf-term-image-preview img' ),
                    idInput = field.find( '.wlpf-term-image-id-input' ),
                    uploadButton = field.find( '.wlpf-term-image-upload-button' ),
                    removeButton = field.find( '.wlpf-term-image-remove-button' ),
                    placeholderUrl = field.attr( 'data-wlpt-placeholder-url' ),
                    fileFrame;

                uploadButton.on( 'click', function( e ) {
                    e.preventDefault();

                    if ( ! fileFrame ) {
                        fileFrame = wp.media.frames.downloadable_file = wp.media( {
                            title: title,
                            button: {
                                text: btnTxt,
                            },
                            multiple: false,
                        } );

                        fileFrame.on( 'select', function () {
                            let attachment = fileFrame.state().get( 'selection' ).first().toJSON();
                            let attachmentThumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                            imgTag.attr( 'src', attachmentThumbnail.url );
                            idInput.val( attachment.id );
                            removeButton.show();
                        } );
                    }

                    fileFrame.open();
                } );

                removeButton.on( 'click', function( e ) {
                    e.preventDefault();

                    imgTag.attr( 'src', placeholderUrl );
                    idInput.val( '' );
                    removeButton.hide();

                    return false;
                } );
            } );
        } )();

    } );

} )( jQuery );