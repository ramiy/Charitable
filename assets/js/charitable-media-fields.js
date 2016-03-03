( function( $, document ){
    $(document).ready( function(){
        var frames = {};

        var get_attachment_size = function( attachment, size, fallback ) {
            var attachment_size = attachment.sizes[ size ];

            if ( ! attachment_size ) {
                attachment_size = attachment.sizes[ fallback ];
            }

            return attachment_size;
        }

        $( '.charitable-media-upload' ).each( function() {
            var $this = $( this ),
                label = $this.data( 'upload-label' ),
                key = $this.data( 'key' );
            
            if ( $this.find( 'img' ).length ) {
                label = $this.data( 'change-label' );
            }

            $this.find( 'input[type=file]' ).remove();
            $this.append( '<a href="#" class="upload">' + label + '</a>' )
                .append( '<input type="hidden" name="' + key + '" value="" />' );
        });

        $( '.charitable-media-upload .upload' ).on( 'click', function( event ) {

            var $this = $( this ),
                $wrapper = $this.parent( '.charitable-media-upload' ),
                $img = $wrapper.find( 'img' ), 
                size = $wrapper.data( 'size' ) || 'thumbnail', 
                media = $wrapper.data( 'media' ) || 'image', 
                multiple = $wrapper.data( 'multiple' ) || false,
                user = $wrapper.data( 'user' ), 
                key = $wrapper.data( 'key' ),                
                label = $wrapper.data( 'upload-label' ),
                button = $wrapper.data( 'upload-button' ),
                frame = frames[ key ];

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( frame ) {
                frame.open();
                return;
            }

            // Create the media frame.
            frame = wp.media.frames.file_frame = wp.media({
                title: label,
                button: {
                    text: button,
                },
                library: {
                    author: user
                },
                multiple: multiple
            });

            // When an image is selected, run a callback.
            frame.on( 'select', function() {

                // We set multiple to false so only get one image from the uploader
                var attachment = frame.state().get('selection').first().toJSON();

                // Use attachment.id to get the image preview
                var image = get_attachment_size( attachment, size, 'thumbnail' );

                console.log( image );

                if ( $img.length > 0 ) {
                    $img.remove();
                }

                $wrapper.prepend( '<img src="' + image.url + '" width="' + image.width + '" height="' + image.height + '" />' );                    

                $img = $this.find( 'img' );

                $wrapper.find( 'input[name=' + key + ']' ).val( attachment.id );
            });

            // Finally, open the modal
            frame.open();

            frames[ key ] = frame;
        });
    });
})( jQuery, document );