<?php

function myfossil_fossil_render_single_history( $fossil ) {
    global $activities_template;

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
        <div class="timeline-centered">

        <?php foreach( $activities_template->activities as $activity ) : ?>
            <article class="timeline-entry">
                <div class="timeline-entry-inner">
                    <div class="timeline-icon">
                        <?=get_avatar( $activity->user_id, 30 ) ?>
                    </div>
                    <div class="timeline-label">
                        <h2>
                            <?=$activity->action ?>
                            <i class="fa fa-fw fa-clock-o"></i>
                            <?=bp_core_time_since( $activity->date_recorded ); ?>
                        </h2>
                        <?=$activity->content ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        </ul>

    <?php endif;
}
