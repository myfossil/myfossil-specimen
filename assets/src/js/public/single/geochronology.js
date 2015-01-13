(function($) {
    'use strict';

    var SCALES = {
        1: 'eon',
        2: 'era',
        3: 'period',
        4: 'epoch',
        5: 'age'
    };

    var SCALE_NAMES = ['eon', 'era', 'period', 'epoch', 'age'];

    function reset_geochronlogy() {
        $.map(['era', 'period', 'epoch', 'age'], function(level) {
            $('#fossil-geochronology-' + level).html(
                '<span class="unknown">Unknown</span>'
            );
        });
    }

    function load_geochronology() {
        var url = "http://paleobiodb.org/data1.1/intervals/list.json" +
            "?scale=1&vocab=pbdb";

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',

            /**
             * Re-organize results from the PBDB.
             * 
             * Certain features are not currently supported by PBDB, so
             * that's why we're doing it here in the client.
             */
            success: function(resp) {
                var intervals = [], match = false;

                resp.records.forEach(function(interval) {
                    intervals[interval.interval_no] = interval;
                    if ($('#fossil-geochronology-name').val() === interval.interval_name) {
                        match = interval.interval_no;
                    }
                });

                /**
                 * Populate parents of the time interval
                 */
                var current_interval = match ? intervals[match] : null;
                while (current_interval) {
                    $('#fossil-geochronology-' + SCALES[current_interval.level])
                        .text(current_interval.interval_name);
                    current_interval = intervals[current_interval.parent_no];
                }

                // Load the data into the select box
                populate_geochronology_select(resp);

                // Let everyone know that we're good to go...
                $('#fossil-geochronology-success').show().fadeOut();
            },
            complete: function(data) {
                $('#fossil-geochronology-loading').hide();
            },
            error: function(err) {
                console.log(err);
                $('#fossil-geochronology-error').show().fadeOut();
            }
        });
    }

    function populate_geochronology_select(data) {
        var select = $('select#edit-fossil-geochronology');

        var optgroups = {}, scale_label;
        for (var level = 1; level <= 5; level++) {
            scale_label = SCALES[level].charAt(0).toUpperCase() + SCALES[level].slice(1);
            optgroups[level] = $('<optgroup />').attr('label', scale_label);
        };

        $.map(data.records, function(time_interval) {
            var option = $('<option></option>')
                .val(time_interval.interval_name)
                .text(time_interval.interval_name)
                .data('color', time_interval.color)
                .data('early_age', parseFloat(time_interval.early_age))
                .data('late_age', parseFloat(time_interval.late_age))
                .data('pbdbid', time_interval.interval_no)
                .data('parent_pbdbid', time_interval.parent_no)
                .data('reference_pbdbid', time_interval.reference_no.pop())
                .data('level', SCALES[time_interval.level])
                .data('name', time_interval.interval_name);

            // Add to optgroup
            optgroups[time_interval.level].append(option);
        });

        for (var level in optgroups) {
            select.append(optgroups[level]);
        }

        select.change(function() {
            var option = $('select#edit-fossil-geochronology option:selected');
            $.map(['name', 'level', 'pbdb', 'color'], function(property) {
                $('#fossil-geochronology-' + property).val(
                    option.data(property)
                );
            });

            reset_geochronlogy();
            load_geochronology();
        });
    }


    // {{{ save_geochronology 
    function save_geochronology() {
            var nonce = $('#myfossil_specimen_nonce').val();
            var post_id = parseInt($('#post_id').val());
            var geochronology_name = $('#fossil-geochronology-name').val(),
                geochronology_level = $('#fossil-geochronology-level').val(),
                geochronology_pbdb = $('#fossil-geochronology-pbdb').val(),
                geochronology_color = $('#fossil-geochronology-color').val();

            var geochronology = {
                name: geochronology_name,
                level: geochronology_level,
                pbdb: geochronology_pbdb,
                color: geochronology_color,
            };

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: {
                    'action': 'myfossil_save_geochronology',
                    'nonce': nonce,
                    'post_id': post_id,
                    'geochronology': geochronology,
                    'comment': $('#edit-fossil-geochronology-comment').val()
                },
                dataType: 'json',
                success: function(data) {
                    console.info(data);
                    $('#fossil-geochronology-success').show().fadeOut();
                    $('#edit-fossil-geochronology-save-alert').fadeOut();
                },
                complete: function(data) {
                    $('#fossil-geochronology-loading').hide();
                },
                error: function(err) {
                    console.error(err);
                    $('#fossil-geochronology-error').show().fadeOut(1000);
                }
            });

        }
        // }}}

    function save_prompt() {
        $('#edit-fossil-geochronology-save-alert').show();
    }

    function toggle_comment() {
        $('#edit-fossil-geochronology-comment-form-group').toggle();
        $(this).fadeOut(400);
        // $( '#edit-fossil-geochronology-comment-toggle > button' ).click( toggle_comment );
    }

    $(function() {
        load_geochronology();

        $('#edit-fossil-geochronology-save').click(save_geochronology);
        $('#edit-fossil-geochronology-comment-toggle > button').click(toggle_comment);

        $('#edit-fossil-geochronology').popup({
            type: 'tooltip',
            opacity: 1,
            background: false,
            transition: 'all 0.2s',
            closetransitionend: save_prompt
        });
    });

}(jQuery));
