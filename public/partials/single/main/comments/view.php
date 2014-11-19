<?php

function fossil_view_comments( $fossil=null ) {
    ?>

    <h3>Discussion</h3>

    <div>
        <?php if (is_user_logged_in()): ?>
            <?php fossil_post_comment_form( $fossil ) ?>
        <?php endif; ?>
    </div>

    <?php
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

        <?php while (bp_activities()): ?>
            <?php bp_the_activity(); ?>
            <?php bp_get_template_part('activity/entry'); ?>
        <?php endwhile; ?>

        <?php if (bp_activity_has_more_items()): ?>
            <li class="load-more">
                <a href="<?php bp_activity_load_more_link() ?>"><?php _e('Load More', 'buddypress'); ?></a>
            </li>
        <?php endif; ?>

        </ul>
    <?php else: ?>
        <div id="message" class="alert alert-info">
            <p><?php _e('Sorry, there was no activity found. Please try a different filter.', 'buddypress'); ?></p>
        </div>
    <?php endif; ?>

    <?php
}
