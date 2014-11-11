<?php

use myFOSSIL\Plugin\Specimen\Stratum;

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
