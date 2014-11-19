<?php

function fossil_edit_taxonomy( $fossil=null ) {
    ?>

    <div id="edit-fossil-taxon" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Taxonomy</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <div class="form-group">
                        <label class="control-label">Taxon</label>
                        <input class="form-control" type="text" 
                                id="edit-fossil-taxon-name"
                                placeholder="Begin typing your Taxon" />
                    </div>
                    <?php edit_comment_box( 'taxon' ); ?>
                </form>
            </div>
            <div class="edit-fossil-footer">
                <ul id="edit-fossil-taxon-results">
                </ul>
            </div>
        </div>
    </div>

    <?php
}
