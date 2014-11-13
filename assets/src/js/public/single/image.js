( function( $ ) {
    'use strict';

    $( function() {
        var post_id = $( '#post_id' ).val();
        var nonce = $( '#myfossil_specimen_nonce' ).val();

        $( '#fossil-upload-image' ).fileupload({
            dataType: 'json',
            formData: {
                action: 'myfossil_upload_fossil_image',
                nonce: nonce,
                post_id: post_id,
            },
            url: ajaxurl,
            success: function( data ) {
                if ( data && data.src ) 
                    $( 'img.fossil-image' ).attr( 'src', data.src );
            },
            done: function( e, data ) {
                console.info( e );
                console.info( data );
            },
            error: function( err ) {
                console.error( err );
            }
        });

        $( '#fossil-delete-image' ).click( function() {
            $.ajax({
                type: 'post',
                url: ajaxurl,
                dataType: 'json',
                data: { 
                        'action' : 'myfossil_delete_fossil_image',
                        'nonce'  : nonce,
                        'image_id': $( '#fossil-featured-image' ).data( 'attachment-id' )
                    },
                success: function( data ) {
                        if ( data == '1' ) {
                            location.reload();
                        }
                    },
                error: function ( err ) {
                        console.error( err );
                    }
            });
        });

    } );

}( jQuery ) );
