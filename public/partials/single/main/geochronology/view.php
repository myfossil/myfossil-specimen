<?php

function fossil_view_geochronology( $fossil=null ) {
    ?>

    <h3>
        Geochronology
        <i class="fa fa-fw fa-circle-o-notch fa-spin"
                id="fossil-geochronology-loading"></i>
        <i style="display: none" class="fa fa-fw fa-check"
                id="fossil-geochronology-success"></i>
        <i style="display: none" class="fa fa-fw fa-warning"
                id="fossil-geochronology-error"></i>
    </h3>

    <?php save_alert( 'geochronology' ); ?>

    <input type="hidden" 
            id="fossil-geochronology-name" 
            value="<?=$fossil->time_interval->name ?>" />
    <table class="table">
        <?php foreach ( array( 'era', 'period', 'epoch', 'age' ) as $n => $k ): ?>
            <tr>
                <td class="fossil-property"><?=ucwords( $k ) ?></td>
                <td class="fossil-property-value<?=( current_user_can( 'edit_post', $fossil->id ) ) ? " edit-fossil-geochronology_open editable" : null ?>"
                        id="fossil-geochronology-<?=$k ?>"
                        data-value="<?=( $fossil->dimension ) ? $fossil->dimension->{ $k } : null ?>"
                        data-edit="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>"
                        data-popup-ordinal="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>">
                    <span class="unknown">Unknown</span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
}
