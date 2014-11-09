( function( $ ) {
    'use strict';

    $( function() {
        $( '.btn-file :file' ).on( 'fileselect', function( ev, nfile, label ) {
                console.log( label );
        });

        $( '.btn-file :file' ).change( function() {
            var input = $( this );
            var nfile = input.get( 0 ).files ? input.get( 0 ).files.length : 1;
            var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger( 'fileselect', [nfile, label] );
        });

    } );

}( jQuery ) );
