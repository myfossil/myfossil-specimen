( function( $ ) {
    'use strict';

    function load_geochronology() {
        var url = "http://paleobiodb.org/data1.1/intervals/list.json"
                + "?scale=1&vocab=pbdb";

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            success: function( resp ) {
                // Re-organize results from the PBFDB.
                var intervals = [], match;
                resp.records.forEach(
                    function( interval ) {
                        intervals[interval.interval_no] = interval;
                        if ( $( '#fossil-time_interval-name' ).val() == interval.interval_name )
                            match = interval.interval_no;
                    }
                );

                var current_interval = intervals[match];

                while ( current_interval ) {
                    $( '#geochronology-' + current_interval.level )
                        .text( current_interval.interval_name );
                    current_interval = intervals[current_interval.parent_no];
                }
            },
            error: function( err ) {
                console.log( err );
            }
        });
    }

    // {{{ save_geochronology 
    function save_geochronology() {
        var nonce = $( '#myfossil_specimen_nonce' ).val(); 
        var post_id = parseInt( $( '#post_id' ).val() );
        var geochronology_name = $( '#fossil-geochronology-name'  ).val(),
            geochronology_rank = $( '#fossil-geochronology-level' ).val(),
            geochronology_pbdb = $( '#fossil-geochronology-pbdb'  ).val(),
            geochronology_pbdb = $( '#fossil-geochronology-color' ).val();
        var geochronology = {
                name  : geochronology_name,
                level : geochronology_level,
                pbdb  : geochronology_pbdb,
                color : geochronology_color,
            };

        $.ajax({
            async: false,
            type: 'post',
            url: ajaxurl,
            data: { 
                    'action': 'myfossil_save_geochronology',
                    'nonce': nonce,
                    'post_id': post_id,
                    'geochronology': geochronology
                },
            dataType: 'json',
            success: function( data ) {
                    $( '#fossil-geochronology-success' ).show().fadeOut();
                },
            complete: function( data ) {
                    $( '#fossil-geochronology-loading' ).hide();
                },
            error: function ( err ) {
                    console.error( err );
                    $( '#fossil-geochronology-error' ).show().fadeOut();
                }
        });

    }
    // }}}

    $( function() {
        load_geochronology();

        $( '#edit-fossil-geochronology' ).popup(
                {
                    type: 'tooltip',
                    opacity: 1,
                    background: false,
                    transition: 'all 0.2s',
                    closetransitionend: save_geochronology,
                    onclose: function() {
                        $( '#fossil-geochronology-loading' ).show();
                    }
                }
            );
    } );

}( jQuery ) );
