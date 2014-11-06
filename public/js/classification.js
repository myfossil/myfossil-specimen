( function( $ ) {
    'use strict';

    function update_taxon( post_id, taxon ) {
        var nonce = $( '#myfossil_specimen_nonce' ).val(); 

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
                    console.log( data );
                },
            error: function ( err ) {
                    console.log( err );
                }
        });
    }

    function reset_taxa() {
        var ranks = ['phylum', 'class', 'order', 'family', 'genus', 'species'];
        
        $.map( ranks, function( rank ) {
                $( '#taxon-' + rank ).html( '<span class="unknown">Unknown</span>' );
            }
        );
    }

    function load_taxa() {
        var url = "http://paleobiodb.org/data1.1/taxa/list.json?name="
                + $( '#fossil-taxon-name' ).val() + "&rel=all_parents&vocab=pbdb";

        reset_taxa();

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            success: function( resp ) {
                resp.records.forEach( 
                    function( taxon ) {
                        taxon = normalize_taxon( taxon );
                        $( '#taxon-' + taxon.rank ).text( taxon.taxon_name );
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

    function taxon_normalize_rank( rank ) {
        var _rank = rank.split( '' );

        if ( _rank.slice( 0, 3 ) == 'sub' )
            return _rank.slice( 3 ).join( '' );

        if ( _rank.slice( 0, 4 ) == 'infra' )
            return _rank.slice( 4 ).join( '' );

        if ( _rank.slice( 0, 5 ) == 'super' )
            return _rank.slice( 5 ).join( '' );

        return rank;
    }

    function normalize_taxon( taxon ) {
        if ( taxon.rank )
            taxon.rank = taxon_normalize_rank( taxon.rank );
        if ( taxon.taxon_rank )
            taxon.taxon_rank = taxon_normalize_rank( taxon.taxon_rank );
        return taxon;
    }

    function set_taxon( taxon ) {
        $( '#fossil-taxon-name' ).val( taxon.taxon_name );
        $( 'td#taxon-' + taxon.taxon_rank ).text( taxon.taxon_name );

        var post_id = parseInt( $( '#post_id' ).val() );
        update_taxon( post_id, taxon );
        load_taxa();
    }

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
                                            set_taxon( taxon );
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

    $( function() {
        load_taxa();
        $( '#edit-fossil-taxon-name' ).keyup( autocomplete_taxon );
        $( '#edit-fossil-taxon' ).popup(
                {
                    ype: 'tooltip',
                    opacity: 1,
                    background: false,
                    transition: 'all 0.2s',
                }
            );
    } );
}( jQuery ) );
