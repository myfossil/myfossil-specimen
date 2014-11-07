( function( $ ) {
    'use strict';

    // {{{ load_taxa
    function load_taxa() {
        var url = "http://paleobiodb.org/data1.1/taxa/list.json?name="
                + $( '#fossil-taxon-name' ).val() + "&rel=all_parents&vocab=pbdb";
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            success: function( resp ) {
                resp.records.forEach( 
                    function( taxon ) {
                        taxon = normalize_taxon( taxon );
                        $( '#fossil-taxon-' + taxon.rank ).text( taxon.taxon_name );
                    }
                );
            },
            error: function( err ) {
                console.log( err );
            }
        });
    }

    function get_taxon_img( taxon_no ) {
        if ( taxon_no <= 0 ) return;

        var url = "http://paleobiodb.org/data1.1/taxa/single.json"
                + "?show=img&vocab=pbdb&id=" 
                + taxon_no;
        var img_url = "http://paleobiodb.org/data1.1/taxa/thumb.png?id=";
        var img = $( '<img />' ).addClass( 'phylopic' );

        // Query the PBDB with the taxon id.
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function( data ) {
                var taxon = data.records.pop();
                if ( taxon.image_no ) {
                    img.attr( 'src', img_url + taxon.image_no );
                }
            }
        });

        return img;
    }
    // }}}

    // {{{ set_taxon
    function set_taxon( taxon ) {
        $( 'td#fossil-taxon-' + taxon.rank ).text( taxon.taxon_name );
        $( '#fossil-taxon-name' ).val( taxon.taxon_name );
        $( '#fossil-taxon-rank' ).val( taxon.taxon_rank );
        $( '#fossil-taxon-pbdb' ).val( taxon.taxon_no );

        reset_taxa();
        load_taxa();
    }
    // }}}
    // {{{ reset_taxa
    function reset_taxa() {
        var ranks = ['phylum', 'class', 'order', 'family', 'genus', 'species'];
        
        $.map( ranks, function( rank ) {
                $( '#fossil-taxon-' + rank ).html( '<span class="unknown">Unknown</span>' );
            }
        );
    }
    // }}}

    // {{{ save_taxon 
    function save_taxon() {
        var nonce = $( '#myfossil_specimen_nonce' ).val(); 
        var post_id = parseInt( $( '#post_id' ).val() );
        var taxon_name = $( '#fossil-taxon-name' ).val(),
            taxon_rank = $( '#fossil-taxon-rank' ).val(),
            taxon_pbdb = $( '#fossil-taxon-pbdb' ).val();
        var taxon = {
                name: taxon_name,
                rank: taxon_rank,
                pbdb: taxon_pbdb,
            };

        $.ajax({
            async: false,
            type: 'post',
            url: ajaxurl,
            data: { 
                    'action': 'myfossil_save_taxon',
                    'nonce': nonce,
                    'post_id': post_id,
                    'taxon': taxon
                },
            dataType: 'json',
            success: function( data ) {
                    $( '#fossil-taxon-success' ).show().fadeOut();
                },
            complete: function( data ) {
                    $( '#fossil-taxon-loading' ).hide();
                },
            error: function ( err ) {
                    console.error( err );
                    $( '#fossil-taxon-error' ).show().fadeOut();
                }
        });

    }
    // }}}

    // {{{ autocomplete_taxon
    function autocomplete_taxon() {
        // PBDB auto-complete requires least 3 characters before returning a
        // response.
        if ( parseInt( $( this ).val().length ) < 3 )
            return;

        // Auto-complete unordered list.
        var ul = $( 'ul#edit-fossil-taxon-results' );

        // @todo Make the PBDB URL some kind of constant.
        var url = "http://paleobiodb.org/data1.1/taxa/auto.json"
                + "?limit=20&vocab=pbdb&name="
                + $( this ).val();

        // Query the PBDB with the current taxon name partial.
        $.ajax(
            {
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function( data ) {
                    // Remove current taxa from the auto-complete list.
                    ul.empty(); 

                    // foreach taxon result from the auto-complete
                    $.map( data.records, function( taxon ) {
                            taxon = normalize_taxon( taxon );

                            // Filter out misspellings.
                            if ( !! taxon.misspelling ) return true;

                            // Build list item, including phylopic.
                            var taxon_li = $( '<li></li>' )
                                    .addClass( 'hover-hand' )
                                    .append( get_taxon_img( taxon.taxon_no ) )
                                    .append( ' ' )
                                    .append( taxon.taxon_name )
                                    .click( function() {
                                            set_taxon( normalize_taxon ( taxon ) );
                                        }
                                    );

                            // Add list item to the results.
                            ul.append( taxon_li );
                        }
                    );
                },
                error: function( err ) { 
                    console.log( err ) 
                }
            }
        );
    }
    // }}}

    $( function() {
        load_taxa();

        $( '#edit-fossil-taxon-name' ).keyup( autocomplete_taxon );

        $( '#edit-fossil-taxon' ).popup(
                {
                    type: 'tooltip',
                    opacity: 1,
                    background: false,
                    transition: 'all 0.2s',
                    closetransitionend: save_taxon,
                    onclose: function() {
                        $( '#fossil-taxon-loading' ).show();
                    }
                }
            );
    } );

    // {{{ normalize_taxon
    function normalize_taxon( taxon ) {
        if ( taxon.rank )
            taxon.rank = _taxon_normalize_rank( taxon.rank );
        else
            taxon.rank = taxon.taxon_rank;

        if ( taxon.taxon_rank )
            taxon.taxon_rank = _taxon_normalize_rank( taxon.taxon_rank );
        else
            taxon.taxon_rank = taxon.rank;

        return taxon;
    }
    // }}}
    // {{{ _taxon_normalize_rank
    function _taxon_normalize_rank( rank ) {
        var _rank = rank.split( '' );

        if ( _rank.slice( 0, 3 ) == 'sub' )
            return _rank.slice( 3 ).join( '' );

        if ( _rank.slice( 0, 4 ) == 'infra' )
            return _rank.slice( 4 ).join( '' );

        if ( _rank.slice( 0, 5 ) == 'super' )
            return _rank.slice( 5 ).join( '' );

        return rank;
    }
    // }}}

}( jQuery ) );

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

( function( $ ) {
    'use strict';

}( jQuery ) );

( function( $ ) {
    'use strict';

    function init_map() {
        var loc = new google.maps.LatLng( 
                parseFloat( $( '#fossil-location-latitude' ).text() ),
                parseFloat( $( '#fossil-location-longitude' ).text() )
            );

        var mapoptions = {
                center: loc,
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            };

        var marker = new google.maps.Marker(
                {
                    position: loc,
                    map: map,
                    title: $( '#fossil-taxon-name' ).val(),
                    clickable: false,
                }
            );

        var map = new google.maps.Map( 
                document.getElementById("map-container"), mapoptions
            );

        marker.setMap(map); 
    }

    function init_location_edit() {
        var keys = [ 'latitude', 'longitude', 'country', 'state', 'county', 'city' ];
        $.map( keys, function( k ) { 
            $( '#edit-fossil-location-' + k).val( 
                    $( '#fossil-location-' + k ).text().trim() 
                );

            $( '#edit-fossil-location-' + k).keyup( function() {
                $( '#fossil-location-' + k).text( $( '#edit-fossil-location-' + k).val() );
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
        google.maps.event.addDomListener(window, 'load', init_map);

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
