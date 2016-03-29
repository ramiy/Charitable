( function( $ ){

    $( '.charitable-drag-drop' ).each( function() {    

        var self = this,
            $dragdrop = $( this ),            
            params = $dragdrop.data( 'params' ),
            $images = $( '#' + $dragdrop.data( 'images' ) ),
            $loader = $dragdrop.find( '.charitable-drag-drop-image-loader' ).first(),
            max_file_uploads = params.multipart_params.max_uploads,
            max_file_size = parseInt( $dragdrop.data( 'max-size' ), 10 ),
            isIE = navigator.userAgent.indexOf('Trident/') != -1 || navigator.userAgent.indexOf('MSIE ') != -1,                    
            msg = get_max_uploads_message( max_file_uploads ), 
            $dropzone, key, error, uploader;        

        uploader = new plupload.Uploader( params );
        
        $dropzone = $( uploader.settings.drop_element );

        uploader.init();

        uploader.bind( 'PostInit', function() {
            
            if ( ! $dropzone ) {
                return;
            }            

            $dragdrop.parent().addClass( 'supports-drag-drop' );

            // We may need to enhance this to account for the issue noted
            // in https://core.trac.wordpress.org/ticket/21705
            $dropzone.bind( 'dragover', function(){
                $dropzone.addClass('drag-over');
            });

            $dropzone.bind( 'dragleave', function(){
                $dropzone.removeClass('drag-over');
            });

            // Set up image remove handler
            $dragdrop.on( 'click', '.remove-image', remove_image );
        });

        // Add files
        uploader.bind( 'FilesAdded', function( uploader, files ) {

            var uploaded = $images.children().length;

            // Remove the drag-over class if it's still on the dropzone.
            $dropzone.removeClass('drag-over');

            // Remove files from queue if the max number of files have been uploaded
            if ( max_file_uploads > 0 && ( uploaded + files.length ) > max_file_uploads ) {

                if ( uploaded < max_file_uploads ) {
                    var diff = max_file_uploads - uploaded;
                    uploader.splice( diff - 1, files.length - diff );
                    files = uploader.files;
                }

                alert( msg );
            }

            // Hide drag & drop section if we have reached the max number of file uploads.
            if ( max_file_uploads > 0 && uploaded + files.length >= max_file_uploads ) {
                hide_dropzone( $dropzone );                
            }

            // Upload files
            plupload.each( files, function( file ) {

                add_image_loader( $loader, file );

                if ( file.size >= max_file_size ) {

                    uploader.removeFile( file );
                    
                    add_image_error( $loader, $dropzone, file, CHARITABLE_UPLOAD_VARS.max_file_size.replace('%1$s', file.name).replace('%2$s', bytes_to_mb( max_file_size ) ) );
                }
            });

            uploader.refresh();
            uploader.start();
        });

        uploader.bind( 'Error', function( uploader, e ){
            console.log( e );
        });

        uploader.bind( 'FileUploaded', function( uploader, file, r ){

            var input, data;

            r = $.parseJSON( r.response );

            if ( ! r.success ) {

                add_image_error( $loader, $dropzone, file, CHARITABLE_UPLOAD_VARS.upload_problem.replace('%s', file.name) );
                return;
                
            }

            // Remove the image from the loader & possibly hide the loader.
            hide_image_loader( $loader, file );

            // Display the image
            $images.append( r.data );
        });

    });

    /**
     * Return the message to be displayed when the max number of file uploads has been reach or exceeded.
     *
     * @param   int max_file_uploads
     * @return  string
     */
    function get_max_uploads_message( max_file_uploads ) {
        var msg = max_file_uploads > 1 ? CHARITABLE_UPLOAD_VARS.max_file_uploads_plural : CHARITABLE_UPLOAD_VARS.max_file_uploads_single;

        return msg.replace( '%d', max_file_uploads );
    };

    /**
     * Add an image loader bar to indicate that an image is being uploaded.
     *
     * @param   $loader
     * @param   array file
     * @return  void
     */
    function add_image_loader( $loader, file ) {
        $loader.fadeIn( 300 )
        $loader.children('.images').append( '<li data-file-id="' + file.id + '" class="">' + file.name + '</li>' );        
    };

    /**
     * Hide the image loader.
     *
     * @param   $loader
     * @param   array file
     * @return  void
     */
    function hide_image_loader( $loader, file ) {
        $loader.find( '[data-file-id=' + file.id + ']' ).remove();

        if ( ! $loader.find('.images li').length ) {
            $loader.hide();
        }        
    };

    /**
     * Remove an image.
     *  
     * @return  void
     */
    function remove_image() {
        var $image = $(this).parent();

        $image.fadeOut( 300, function(){
            this.remove();
        });

        $image.parent().siblings( '.charitable-drag-drop-dropzone' ).fadeIn( 300 );

        return false;
    }

    /**
     * Hide the dropzone.
     *
     * @return  void
     */
    function hide_dropzone( $dropzone ) {
        $dropzone.removeClass('drag-over').fadeOut( 300 );
    }

    /**
     * Return a readable filesize.
     */
    function bytes_to_mb( size ) {
        var i = Math.floor( Math.log(size) / Math.log(1024) );
        return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }

    /**
     * Dequeues the file and displays an error.
     *
     * @param   object $loader
     * @param   object $dropzone
     * @param   object file 
     * @param   string msg
     * @return  void
     */
    function add_image_error( $loader, $dropzone, file, msg ) {
        $dropzone.fadeIn( 300 );

        $loader.find( '[data-file-id=' + file.id + ']' ).addClass( 'error' ).text( msg ).delay( 5000 ).fadeOut( 300, function(){
            hide_image_loader( $loader, file );            
        });
    }

})( jQuery );