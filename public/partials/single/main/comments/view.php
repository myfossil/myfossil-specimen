<?php

function fossil_view_comments( $fossil=null ) {
    ?>

    <h3>Discussion</h3>

    <?php if ( bp_has_activities( bp_ajax_querystring( 'activity&item_id=' . $fossil->id ) ) ) : ?>
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
