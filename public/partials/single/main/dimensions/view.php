<?php

function fossil_view_dimensions( $fossil=null ) {
    ?>

    <h3>
        Dimensions
        <i style="display: none" class="fa fa-fw fa-circle-o-notch fa-spin"
                id="fossil-dimensions-loading"></i>
        <i style="display: none" class="fa fa-fw fa-check"
                id="fossil-dimensions-success"></i>
        <i style="display: none" class="fa fa-fw fa-warning"
                id="fossil-dimensions-error"></i>
    </h3>

    <?php save_alert( 'dimension' ); ?>

    <table class="table">
        <tr class="sr-only">
            <th>Dimension</th>
            <th>Value</th>
        </tr>
        <?php foreach ( array( 'length', 'width', 'height' ) as $k ) { ?>
            <tr>
                <td class="fossil-property"><?=ucwords( $k ) ?></td>
                <td class="fossil-property-value<?=( current_user_can( 'edit_post', $fossil->id ) ) ? " edit-fossil-dimensions_open editable" : null ?>"
                        id="fossil-dimension-<?=$k ?>"
                        data-value="<?=( $fossil->dimension ) ? $fossil->dimension->{ $k } : null ?>"
                        data-edit="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>"
                        data-popup-ordinal="<?=( current_user_can( 'edit_post', $fossil->id ) ) ?>">
                    <?php if ( $fossil->dimension && $v = $fossil->dimension->{ $k } * 100 ): ?>
                        <?=$v ?> cm
                    <?php else: ?>
                        <span class="unknown">Unknown</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
