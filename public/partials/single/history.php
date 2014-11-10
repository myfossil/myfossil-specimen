<?php

function myfossil_fossil_render_single_history( $fossil ) {
    ?>

    <?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&item_id='
                . $fossil->id ) ) : ?>
        <ul id="activity-stream" class="activity-list item-list">
        <?php while ( bp_activities() ) : ?>
            <?php bp_the_activity(); ?>
            <?php bp_get_template_part('activity/entry'); ?>
        <?php endwhile; ?>
        </ul>

    <?php endif;
}
