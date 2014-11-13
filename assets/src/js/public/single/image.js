( function( $ ) {
    'use strict';

    function status_loading() {
        var img = $( 'img.fossil-image' );
        var _parent = img.parent();

        console.log( '__status_loading__' );
        img.hide();
        _parent.append( '<span class="loading"><i class="fa fa-spinner fa-spin"></i> Uploading... </span>');
    }

    function status_done() {
        var img = $( 'img.fossil-image' );
        var _parent = img.parent();
        
        console.log( '__status_done__' );

        $( 'span.loading' ).hide();
        img.show();
    }

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
            start: function() {
                status_loading();
            },
            success: function( data ) {
                if ( data && data.src ) 
                    $( 'img.fossil-image' ).attr( 'src', data.src );
            },
            done: function( e, data ) {
                console.info( e );
                console.info( data );
                status_done();
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
