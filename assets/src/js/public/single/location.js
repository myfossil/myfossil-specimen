( function( $ ) {
    'use strict';

    function init_map() {
        var mapNode = document.getElementById( 'fossil-map-container' );
        if ( ! mapNode ) return; // bail if no mapNode to make map

        var loc = new google.maps.LatLng( 
                parseFloat( $( '#fossil-location-latitude' ).text() ),
                parseFloat( $( '#fossil-location-longitude' ).text() )
            );

        var mapOptions = {
                center: loc,
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            };

        var marker = new google.maps.Marker({
            position: loc,
            title: $( '#fossil-taxon-name' ).val(),
            clickable: false,
        }).setMap( new google.maps.Map( mapNode, mapOptions ) );
    }

    function init_location_edit() {
        var keys = [ 'latitude', 'longitude', 'country', 'state', 'county', 'city' ];
        $.map( keys, function( k ) { 
            $( '#edit-fossil-location-' + k).val( 
                    $( '#fossil-location-' + k ).data( 'value' )
                );

            $( '#edit-fossil-location-' + k).keyup( function() {
                $( '#fossil-location-' + k)
                        .text( $( '#edit-fossil-location-' + k).val() )
                        .data( 'value', $( '#edit-fossil-location-' + k).val() );
            });
        });
    }


    // {{{ save_location 
    function save_location() {
        var nonce = $( '#myfossil_specimen_nonce' ).val(); 
        var post_id = parseInt( $( '#post_id' ).val() );
        var loc = {};

        var keys = [ 'latitude', 'longitude', 'country', 'state', 'county', 'city' ];
        $.map( keys, function( k ) { 
            loc[k] = $( '#edit-fossil-location-' + k).val(); 
        });

        $.ajax({
            async: false,
            type: 'post',
            url: ajaxurl,
            data: { 
                    'action': 'myfossil_save_location',
                    'nonce': nonce,
                    'post_id': post_id,
                    'location': loc
                },
            dataType: 'json',
            success: function( data ) {
                    $( '#fossil-location-success' ).show().fadeOut();
                },
            complete: function( data ) {
                    $( '#fossil-location-loading' ).hide();
                },
            error: function ( err ) {
                    console.error( err );
                    $( '#fossil-location-error' ).show().fadeOut();
                }
        });

    }
    // }}}

    $( function() {
        google.maps.event.addDomListener( window, 'load', init_map );

        init_location_edit();

        $( '#edit-fossil-location' ).popup(
                {
                    type: 'tooltip',
                    opacity: 1,
                    background: false,
                    transition: 'all 0.2s',
                    closetransitionend: save_location,
                    onclose: function() {
                        $( '#fossil-location-loading' ).show();
                    }
                }
            );
    } );

}( jQuery ) );
