( function( $ ) {
    'use strict';

    function update_taxon( post_id, taxon ) {
        var nonce = $( '#myfossil_specimen_nonce' ).val(); 
        var json = null;

        $.ajax({
            async: false,
            type: 'post',
            url: ajaxurl,
            data: { 
                    'action': 'myfossil_update_taxon',
                    'nonce': nonce,
                    'post_id': post_id,
                    'taxon': taxon
                },
            dataType: 'json',
            success: function( data ) {
                    json = data;
                },
            error: function ( err ) {
                    console.log( err );
                }
        });

        return json;
    }

}( jQuery ) );
