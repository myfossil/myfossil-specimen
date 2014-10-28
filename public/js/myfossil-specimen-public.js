/*
( function( $ ) {
    'use strict';

    function load_taxa() {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: { 
                'action': 'myfs_load_default_fossils',
                'nonce': nonce
            },
            success: function( resp ) {
                if ( parseInt( resp ) == 1 ) {
                    $( '#message' ).html( 
                            '<p>Loaded default time intervals into fossils.</p>'
                        ).addClass( 'updated' );
                } else {
                    $( '#message' ).html( 
                            '<p>Failed to load default data into fossils.</p>'
                        ).addClass( 'error' );
                    console.log( resp );
                }
            },
            error: function( err ) {
                console.log( err );
            }
        });
    }

    $( function() {
        load_taxa();
    } );

}( jQuery ) );
*/
