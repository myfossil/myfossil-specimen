<?php

function fossil_edit_location( $fossil=null ) {
    ?>

    <div id="edit-fossil-location" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Location</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <?php $loc_keys = array( 'latitude', 'longitude', 'country',
                            'state', 'county', 'city', ); ?>
                    <?php foreach ( $loc_keys as $k ): ?>
                        <div class="form-group">
                            <label for="edit-fossil-location-<?=$k ?>">
                                <?=ucfirst( $k ) ?>
                            </label>
                            <input type="text" 
                                    class="form-control" 
                                    id="edit-fossil-location-<?=$k ?>" />
                        </div>
                    <?php endforeach; ?>
                    <?php edit_comment_box( 'location' ); ?>
                </form>
            </div>
            <div class="edit-fossil-footer">
            </div>
        </div>
    </div>

    <?php
}

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

function fossil_location( $fossil=null ) {
    fossil_view_location( $fossil );
    fossil_edit_location( $fossil );
}
