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
            done: function( e, data ) {
                console.log( e );
                console.log( data );
            },
            error: function( err ) {
                console.error( err );
            }
        });

    } );

}( jQuery ) );
