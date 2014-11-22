<?php

use myFOSSIL\Plugin\Specimen\Stratum;

function fossil_view_lithostratigraphy( $fossil=null ) { 
    ?>

    <h3>
        Lithostratigraphy
        <i style="display: none" class="fa fa-fw fa-circle-o-notch fa-spin"
                id="fossil-lithostratigraphy-loading"></i>
        <i style="display: none" class="fa fa-fw fa-check"
                id="fossil-lithostratigraphy-success"></i>
        <i style="display: none" class="fa fa-fw fa-warning"
                id="fossil-lithostratigraphy-error"></i>
    </h3>

    <?php save_alert( 'lithostratigraphy' ); ?>

    <table class="table">
        <?php foreach ( Stratum::get_ranks() as $n => $k ): ?>
            <tr>
                <td class="fossil-property"><?=ucwords( $k ) ?></td>
                <td class="fossil-property-value<?=( current_user_can( 'edit_post', $fossil->id ) ) ? " edit-fossil-stratum-{$k}_open editable" : null ?>"
                        id="fossil-stratum-<?=$k ?>"
                        data-edit="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>"
                        data-popup-ordinal="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>"
                        data-name="<?=( property_exists( $fossil->strata, $k ) ) ? $fossil->strata->{ $k }->name : null ?>">
                    <?php if ( property_exists( $fossil->strata, $k ) && $fossil->strata->{ $k }->name ): ?>
                        <?=$fossil->strata->{ $k }->name ?>
                    <?php else: ?>
                        <span class="unknown">Unknown</span>
                    <?php endif; ?> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
}
