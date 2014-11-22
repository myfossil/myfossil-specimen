<?php

function fossil_view_location( $fossil=null ) {
    $hide_fields = array( 'zip', 'address', 'map_url' );
    ?>

    <h3 style="margin: 20px 0">
        Location
        <i style="display: none" class="fa fa-fw fa-circle-o-notch fa-spin"
                id="fossil-location-loading"></i>
        <i style="display: none" class="fa fa-fw fa-check"
                id="fossil-location-success"></i>
        <i style="display: none" class="fa fa-fw fa-warning"
                id="fossil-location-error"></i>
    </h3>

    <?php save_alert( 'location' ); ?>

    <?php if ( $fossil->location && $fossil->location->latitude && $fossil->location->longitude ): ?>
        <div class="edit-fossil hidden-xs hidden-sm col-md-6 col-lg-6">
            <div class="edit-fossil-body">
                <div style="height: 300px" id="fossil-map-container"></div>
            </div>
        </div>
    <?php else: ?>
        <div class="hidden-xs hidden-sm col-md-6 col-lg-6" style="height: 300px; background-color: #eee;">
            <p class="unknown" style="position: absolute; top: 45%; width: 100%; text-align: center">
                Insufficient information available to create map
            </p>
        </div>
    <?php endif; ?>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <table class="table">
            <?php foreach ( $fossil->location->get_meta_keys() as $k ) : ?>
                <?php if ( ! in_array( $k, $hide_fields ) ) : ?>
                    <tr>
                        <td class="fossil-property"><?=ucwords( $k ) ?></td>
                        <td class="fossil-property-value<?=( current_user_can( 'edit_post', $fossil->id ) ) ? " edit-fossil-location_open editable" : null ?>"
                                id="fossil-location-<?=$k ?>"
                                data-value="<?=( $fossil->location ) ? $fossil->location->{ $k } : null ?>"
                                data-edit="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>"
                                data-popup-ordinal="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>">
                            <?php if ( $fossil->location && $v = $fossil->location->{ $k } ): ?>
                                <?=$v ?>
                            <?php else: ?>
                                <span class="unknown">Unknown</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
}
