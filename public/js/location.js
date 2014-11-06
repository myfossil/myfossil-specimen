( function( $ ) {
    'use strict';

    function init_map() {
        var var_location = new google.maps.LatLng( 
                $( '#fossil-location-latitude' ).val(),
                $( '#fossil-location-longitude' ).val()
            );

        var var_mapoptions = {
                center: var_location,
                zoom: 14
            };

        var var_marker = new google.maps.Marker(
                {
                    position: var_location,
                    map: var_map,
                    title: $( '#fossil-taxon-name' ).val(),
                    clickable: false,
                }
            );

        var var_map = new google.maps.Map( 
                document.getElementById("map-container"), var_mapoptions
            );

        var_marker.setMap(var_map); 
    }

    $( function() {
        google.maps.event.addDomListener(window, 'load', init_map);
    } );

}( jQuery ) );
