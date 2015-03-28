(function($) {
    'use strict';

    // {{{ load_taxa
    function load_taxa() {
            reset_taxa();

            var url = "http://paleobiodb.org/data1.1/taxa/list.json?name=" + $('#fossil-taxon-name').val() + "&rel=all_parents&vocab=pbdb";
            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                success: function(resp) {
                    resp.records.forEach(
                        function(taxon) {
                            taxon = normalize_taxon(taxon);
                            $('#fossil-taxon-' + taxon.rank).text(taxon.taxon_name);
                            $('#fossil-taxon-name').val(taxon.taxon_name);
                            $('#fossil-taxon-rank').val(taxon.taxon_rank);
                            $('#fossil-taxon-pbdb').val(taxon.taxon_no);
                        }
                    );
                    $('#fossil-taxon-success').show().fadeOut();
                },
                complete: function(data) {
                    $('#fossil-taxon-loading').hide();
                },
                error: function(err) {
                    console.log(err);
                    $('#fossil-taxon-error').show().fadeOut();
                }
            });

        }
        // }}}

    // {{{ get_taxon_img
    function get_taxon_img(taxon_no) {
            if (taxon_no <= 0) return;

            var url = "http://paleobiodb.org/data1.1/taxa/single.json" + "?show=img&vocab=pbdb&id=" + taxon_no;
            var img_url = "http://paleobiodb.org/data1.1/taxa/thumb.png?id=";
            var img = $('<img />').addClass('phylopic');

            // Query the PBDB with the taxon id.
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var taxon = data.records.pop();
                    if (taxon.image_no) {
                        img.attr('src', img_url + taxon.image_no);
                    }
                }
            });

            return img;
        }
        // }}}

    // {{{ set_taxon
    function set_taxon(taxon) {
            $('td#fossil-taxon-' + taxon.rank).value(taxon.taxon_name);
            $('#fossil-taxon-name').val(taxon.taxon_name);
            $('#fossil-taxon-rank').val(taxon.taxon_rank);
            $('#fossil-taxon-pbdb').val(taxon.taxon_no);

            load_taxa();
            save_prompt();
        }
        // }}}

    // {{{ reset_taxa
    function reset_taxa() {
            var ranks = ['phylum', 'class', 'order', 'family', 'genus', 'species'];

            $.map(ranks, function(rank) {
                $('#fossil-taxon-' + rank).html('<span class="unknown">Unknown</span>');
            });
        }
        // }}}

    // {{{ save_taxon 
    function save_taxon() {
            var nonce = $('#myfossil_specimen_nonce').val();
            var post_id = parseInt($('#post_id').val());
            var taxon_name = $('#fossil-taxon-name').val(),
                taxon_rank = $('#fossil-taxon-rank').val(),
                taxon_pbdb = $('#fossil-taxon-pbdb').val(),
                taxon_comment = $('#edit-fossil-taxon-comment').val();

            var taxon = {
                name: taxon_name,
                rank: taxon_rank,
                pbdb: taxon_pbdb,
                comment: taxon_comment,
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
                success: function(data) {
                    $('#fossil-taxon-success').show().fadeOut();
                    $('#edit-fossil-taxon-save-alert').fadeOut();
                    console.info(data);
                },
                complete: function(data) {
                    $('#fossil-taxon-loading').hide();
                },
                error: function(err) {
                    console.error(err);
                    $('#fossil-taxon-error').show().fadeOut();
                }
            });

        }
        // }}}

    // {{{ autocomplete_taxon
    function autocomplete_taxon() {
            var input = this;

            // PBDB auto-complete requires least 3 characters before returning a
            // response.
            if (parseInt($(input).val().length) < 3)
                return;

            // Auto-complete unordered list.
            var ul = $('ul#edit-fossil-taxon-results');

            // @todo Make the PBDB URL some kind of constant.
            var url = "http://paleobiodb.org/data1.1/taxa/auto.json" + "?limit=20&vocab=pbdb&name=" + $(this).val();

            var results = [];

            // Query the PBDB with the current taxon name partial.
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Remove current taxa from the auto-complete list.
                    ul.empty();

                    // foreach taxon result from the auto-complete
                    $.map(data.records, function(taxon) {
                        taxon = normalize_taxon(taxon);

                        // Filter out misspellings.
                        if (!!taxon.misspelling) return true;

                        // Deduplicate
                        if ($.inArray(taxon.taxon_name, results) !== -1)
                            return true;
                        else
                            results.push(taxon.taxon_name);

                        // Build list item, including phylopic.
                        var taxon_li = $('<li></li>')
                            .addClass('hover-hand')
                            .append(get_taxon_img(taxon.taxon_no))
                            .append(' ')
                            .append(taxon.taxon_name)
                            .click(function() {
                                set_taxon(normalize_taxon(taxon));
                            });

                        // Add list item to the results.
                        ul.append(taxon_li);
                    });
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
        // }}}

    function save_prompt() {
        $('#edit-fossil-taxon-save-alert').show();
    }

    function toggle_comment() {
        $('#edit-fossil-taxon-comment-form-group').toggle();
        $(this).fadeOut(400);
    }

    $(function() {
        load_taxa();

        $('#edit-fossil-taxon-save').click(save_taxon);
        $('#edit-fossil-taxon-comment-toggle > button').click(toggle_comment);

        $('#edit-fossil-taxon-name').keyup(autocomplete_taxon);

        $('#edit-fossil-taxon').popup({
            type: 'tooltip',
            opacity: 1,
            background: false,
            transition: 'all 0.2s',
        });
    });

    // {{{ normalize_taxon
    function normalize_taxon(taxon) {
            if (taxon.rank)
                taxon.rank = _taxon_normalize_rank(taxon.rank);
            else
                taxon.rank = taxon.taxon_rank;

            if (taxon.taxon_rank)
                taxon.taxon_rank = _taxon_normalize_rank(taxon.taxon_rank);
            else
                taxon.taxon_rank = taxon.rank;

            return taxon;
        }
        // }}}
        // {{{ _taxon_normalize_rank
    function _taxon_normalize_rank(rank) {
            var _rank = rank.split('');

            if (_rank.slice(0, 3) === 'sub')
                return _rank.slice(3).join('');

            if (_rank.slice(0, 4) === 'infra')
                return _rank.slice(4).join('');

            if (_rank.slice(0, 5) === 'super')
                return _rank.slice(5).join('');

            return rank;
        }
        // }}}

}(jQuery));
