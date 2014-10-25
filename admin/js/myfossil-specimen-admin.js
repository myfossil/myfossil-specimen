( function( $ ) {
    'use strict';

    /**
     * Instruct WordPress to load default taxonomic data
     */
    function load_taxonomy_terms() {
        var button_text = 'Load default data',
            spinner_tpl = '<i class="fa fa-fw fa-circle-o-notch fa-spin"></i>';
        var nonce = $( '#myfs_nonce' ).val();

        $( '#load' ).prepend( spinner_tpl );

        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: { 
                'action': 'myfs_load_terms',
                'nonce': nonce
            },
            success: function( resp ) {
                if ( parseInt( resp ) == 1 ) {
                    $( '#message' ).html( 
                            '<p>Loaded default terms into taxonomies successfully.</p>'
                        ).addClass( 'updated' );
                } else {
                    $( '#message' ).html( 
                            '<p>Failed to load default data into taxonomies.</p>'
                        ).addClass( 'error' );
                    console.log( resp );
                }
            },
            error: function( err ) {
                console.log( err );
            }
        });

        $( '#load' ).text( button_text );
    }

    /**
     * Instruct WordPress to reset all taxonomy terms
     */
    function reset_taxonomy_terms() {
        $.post( ajaxurl, { action: 'reset' }, 
            function( response ) { console.log( response ); }
        );
    }

    $( function() {
        $( '#load' ).click( load_taxonomy_terms );
    } );

}( jQuery ) );
