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

    <table class="table">
        <?php foreach ( Stratum::get_ranks() as $n => $k ): ?>
            <tr>
                <td class="fossil-property"><?=ucwords( $k ) ?></td>
                <td class="fossil-property-value"
                        id="fossil-stratum-<?=$k ?>"
                        data-name="<?=( property_exists( $fossil->strata, $k ) ) ? $fossil->strata->{ $k }->name : null ?>">
                    <?php if ( property_exists( $fossil->strata, $k ) ): ?>
                        <?=$fossil->strata->{ $k }->name ?>
                    <?php else: ?>
                        <span class="unknown">Unknown</span>
                    <?php endif; ?> 
                </td>
                <td class="fossil-property-options">
                    <a class="edit-fossil-stratum-<?=$k ?>_open" data-popup-ordinal="1">
                        <i class="ion-compose"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
}
