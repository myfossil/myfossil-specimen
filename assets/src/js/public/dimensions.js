( function( $ ) {
    'use strict';

    function init_dimensions() { 
        var dims = [ 'length', 'width', 'height' ];

        $.map( dims, function( dim ) {
            var input = $( 'input#edit-fossil-dimension-' + dim );
            var view = $( 'td#fossil-dimension-' + dim );

            if ( $.isNumeric( view.attr( 'value' ) ) )
                input.val( view.attr( 'value' ) );

            input.keyup( function() {
                if ( $.isNumeric( input.val() ) )
                    view.text( input.val() + ' cm' ).attr( 'value', input.val() );
            });
        });
    }

    function save_dimensions() {
        var post_id = $( '#post_id' ).val(),
            nonce   = $( '#myfossil_specimen_nonce'      ).val(),
            length  = $( '#edit-fossil-dimension-length' ).val(),
            width   = $( '#edit-fossil-dimension-width'  ).val(),
            height  = $( '#edit-fossil-dimension-height' ).val();

        $.ajax({
            async: false,
            type: 'post',
            url: ajaxurl,
            dataType: 'json',
            data: { 
                    'action' : 'myfossil_save_dimensions',
                    'nonce'  : nonce,
                    'post_id': post_id,
                    'length' : length,
                    'width'  : width,
                    'height' : height
                },
            success: function( data ) {
                    console.log( data );
                    $( '#fossil-dimensions-success' ).show().fadeOut();
                },
            complete: function( data ) {
                    $( '#fossil-dimensions-loading' ).hide();
                    init_dimensions();
                },
            error: function ( err ) {
                    console.error( err );
                    $( '#fossil-dimensions-error' ).show().fadeOut();
                }
        });
    }

    $( function() {
        init_dimensions();

        $( '#edit-fossil-dimensions' ).popup(
                {
                    type: 'tooltip',
                    opacity: 1,
                    background: false,
                    transition: 'all 0.2s',
                    closetransitionend: save_dimensions,
                    onclose: function() {
                        $( '#fossil-dimensions-loading' ).show();
                    }
                }
            );

    } );

}( jQuery ) );
