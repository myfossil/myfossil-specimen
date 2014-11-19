<?php

function save_alert( $id, $message=null ) {
    ?>

    <div class="alert-save" id="edit-fossil-<?=$id ?>-save-alert" style="display: none">
        <div class="message">
            You have unsaved changes.
        </div>
        <div class="actions">
            <?php 
            /*
            <button class="btn btn-default btn-sm" id="edit-fossil-<?=$id ?>-reset">
                <i class="fa fa-fw fa-undo"></i>
                Reset
            </button> 
           */
            ?>

            <button class="btn btn-default btn-sm" id="edit-fossil-<?=$id ?>-save">
                <i class="fa fa-fw fa-save"></i>
                Save
            </button>
        </div>
    </div>

    <?php
}


function edit_comment_box( $id ) {
    ?>
        <div id="edit-fossil-<?=$id ?>-comment-toggle">
            <button type="button" class="btn btn-sm btn-block btn-default">
                <i class="fa fa-fw fa-comment"></i>
                Add comment
            </button>
        </div>
        <div style="display: none" 
                class="form-group" 
                id="edit-fossil-<?=$id ?>-comment-form-group">
            <label class="control-label" for="edit-fossil-<?=$id ?>-comment">
                Comment
            </label>
            <textarea class="form-control" 
                    id="edit-fossil-<?=$id ?>-comment" 
                    name="edit-fossil-<?=$id ?>-comment"
                    placeholder="Comment"></textarea>
        </div>
    <?php
}
