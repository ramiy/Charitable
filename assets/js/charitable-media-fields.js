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

<<<<<<< 48b041893a085be1192da5fc19de619fdd6c75a9
        var get_selection = function( $wrapper, key ) {
            var selection = [];

            // console.log( $wrapper.find( '[name=' + key + ']' ).add( '[name=' + key + '][]' ) );

            $wrapper.find( 'input' ).each( function() {
                selection.push( parseInt( this.value ) );
            });

            console.log( selection );

            return selection;
        };

        $( '.charitable-media-upload[data-uploader=1]' ).each( function() {
=======
        $( '.charitable-media-upload' ).each( function() {
>>>>>>> First steps towards supporting picture upload with the picture form field.
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

<<<<<<< 48b041893a085be1192da5fc19de619fdd6c75a9
        $( '.charitable-media-upload[data-uploader=1] .upload' ).on( 'click', function( event ) {
=======
        $( '.charitable-media-upload .upload' ).on( 'click', function( event ) {
>>>>>>> First steps towards supporting picture upload with the picture form field.

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
<<<<<<< 48b041893a085be1192da5fc19de619fdd6c75a9
                frame = frames[ key ], 
                selection = get_selection( $wrapper, key );
=======
                frame = frames[ key ];
>>>>>>> First steps towards supporting picture upload with the picture form field.

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
<<<<<<< 48b041893a085be1192da5fc19de619fdd6c75a9
                multiple: multiple,
                displaySettings: false
=======
                multiple: multiple
>>>>>>> First steps towards supporting picture upload with the picture form field.
            });

            // When an image is selected, run a callback.
            frame.on( 'select', function() {

                // We set multiple to false so only get one image from the uploader
<<<<<<< 48b041893a085be1192da5fc19de619fdd6c75a9
                var length = frame.state().get('selection').length,
                    images = frame.state().get('selection').models,
                    i = 0,
                    image;

                if ( multiple ) {
                    $wrapper.find( 'input[name=' + key + ']' ).remove();
                }

                for ( i; i < length; i++ ) {

                    console.log( images[ i ] );

                    image = get_attachment_size( images[ i ].changed, size, 'thumbnail' );

                    if ( Number.isInteger( size ) ) {
                        var width = height = size;
                    }
                    else {
                        var width = image.width;
                        var height = image.height;
                    }

                    if ( $img.length > 0 ) {
                        $img.remove();
                    }

                    $wrapper.prepend( '<img src="' + image.url + '" width="' + width + '" height="' + height + '" />' );

                    if ( multiple ) {
                        $wrapper.append( '<input type="hidden" name="' + key + '[]" value="' + images[ i ].id + '" />' );
                    }
                    else {
                        $wrapper.find( 'input[name=' + key + ']' ).val( images[ i ].id );
                    }                    
                }                

                $img = $this.find( 'img' );
                selection = get_selection( $wrapper, key );

                console.log( selection );
=======
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
>>>>>>> First steps towards supporting picture upload with the picture form field.
            });

            // Finally, open the modal
            frame.open();

            frames[ key ] = frame;
        });
    });
})( jQuery, document );