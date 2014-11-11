<?php

function myfossil_fossil_render_single_history( $fossil ) {

    $item_id_keys = array( 'id', 'taxon_id', 'location_id',
            'time_interval_id', 'stratum_formation_id', 'stratum_group_id',
            'stratum_member_id', 'dimension_id', 'reference_id' );
    $item_ids = array();
    foreach ( $item_id_keys as $key )
        if ( $fossil->{ $key } )
            array_push( $item_ids, $fossil->{ $key } );

    $item_query = implode( ',', $item_ids );
    ?>

    <?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) .
                '&primary_id=' . $item_query ) ) : ?>
        <ul id="activity-stream" class="activity-list item-list">
        <?php while ( bp_activities() ) : ?>
            <?php bp_the_activity(); ?>
            <?php bp_get_template_part('activity/entry'); ?>
        <?php endwhile; ?>
        </ul>

    <?php endif;
}
