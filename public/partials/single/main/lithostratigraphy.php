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


function fossil_edit_lithostratigraphy( $fossil=null ) {
    foreach ( Stratum::get_ranks() as $rank ): 
    ?>

    <div id="edit-fossil-stratum-<?=$rank ?>" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Lithostratigraphy</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <div class="form-group">
                        <label class="control-label"><?=ucfirst( $rank ) ?></label>
                        <input data-rank="<?=$rank ?>" 
                                class="form-control"
                                type="text" 
                                id="edit-fossil-stratum-<?=$rank ?>"
                                placeholder="Begin typing a <?=ucfirst( $rank ) ?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sort By</label>
                        <select class="form-control" data-rank="<?=$rank ?>"
                                id="edit-fossil-stratum-<?=$rank ?>-sortby">
                            <option value="name">Name</option>
                            <option value="occs">Number of Occurences</option>
                            <option value="colls">Number of Collections</option>
                        </select>
                    </div>
                    <?php edit_comment_box( 'lithostratigraphy' ); ?>
                </form>
            </div>
            <div class="edit-fossil-footer">
                <ul id="edit-fossil-stratum-<?=$rank ?>-results">
                </ul>
            </div>
        </div>
    </div>

    <?php 
    endforeach;
}

function fossil_lithostratigraphy( $fossil=null ) {
    fossil_view_lithostratigraphy( $fossil );
    fossil_edit_lithostratigraphy( $fossil );
}
