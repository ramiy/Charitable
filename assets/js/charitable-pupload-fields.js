( function ( $ ) {
    'use strict';

    console.log( CHARITABLE_UPLOAD_VARS );

    $( '.charitable-drag-drop' ).each( function() {

        var $drop_area = $( this ),
            $images = $drop_area.siblings( '.charitable-uploaded' ),
            uploader_data = $drop_area.data( 'js-options' ),
            uploader;

        uploader_data.multipart_params = $.extend( {
                '_ajax_nonce' : $drop_area.data( 'upload-nonce' ),
               'post_id' : $drop_area.data( 'post-id' )
            },
            uploader_data.multipart_params
        );

        // Create the uploader
        uploader = new plupload.Uploader( uploader_data );
        uploader.init();

        // Add files
        uploader.bind( 'FilesAdded', function( up, files ) {

            var max_file_uploads = $images.data( 'max_file_uploads' ),
                uploaded = $images.children().length,
                msg = max_file_uploads > 1 ? CHARITABLE_UPLOAD_VARS.max_file_uploads_plural : CHARITABLE_UPLOAD_VARS.max_file_uploads_single;

            msg = msg.replace( '%d', max_file_uploads );

            // Remove files from queue if the max number of files have been uploaded
            if ( max_file_uploads > 0 && ( uploaded + files.length ) > max_file_uploads ) {

                if ( uploaded < max_file_uploads ) {
                    var diff = max_file_uploads - uploaded.
                    up.splice( diff - 1, files.length - diff );
                    files = up.files;
                }

                alert( msg );
            }

            // Hide drag & drop section if we have reached the max number of file uploads.
            if ( max_file_uploads > 0 && uploaded + files.length >= max_file_uploads ) {
                $drop_area.addClass( 'hidden' );
            }

            var max = parseInt( up.settings.max_file_size, 10 );

            // Upload files
            plupload.each( files, function( file ) {
                addLoading( up, file, $images );
                addThrobber( file );
                if ( file.size >= max ) {
                    removeError( file );
                }
            });

            up.refresh();
            up.start();
        });

        uploader.bind( 'Error', function( up, e ){

            console.log( e );
            console.log( up );

        });

        uploader.bind( 'FileUploaded', function( up, file, r ){

            r = $.parseJSON( r.response );

            console.log( r );
            // console.log( up );
            // console.log( file );

        });

    });

    /**
     * Removes li element if there is an error with the file
     *
     * @return void
     */
    function removeError( file ) {
        $( 'li#' + file.id )
            .addClass( 'charitable-image-error' )
            .delay( 1600 )
            .fadeOut( 'slow', function ()
            {
                $( this ).remove();
            } );
    }

    /**
     * Adds loading li element
     *
     * @return void
     */
    function addLoading( up, file, $ul ) {
        $ul.removeClass( 'hidden' ).append( '<li id="' + file.id + '"><div class="charitable-image-uploading-bar"></div><div id="' + file.id + '-throbber" class="charitable-image-uploading-status"></div></li>' );
    }

    /**
     * Adds loading throbber while waiting for a response
     *
     * @return void
     */
    function addThrobber( file ) {
        $( '#' + file.id + '-throbber' ).html( '<img class="charitable-loader" height="64" width="64" src="' + CHARITABLE_VARS.loading_gift + '">' );
    }

})( jQuery );