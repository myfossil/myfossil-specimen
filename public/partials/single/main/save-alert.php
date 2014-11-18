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
