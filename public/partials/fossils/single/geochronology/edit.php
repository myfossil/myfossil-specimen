<?php

function fossil_edit_geochronology( $fossil=null ) {
    ?>

    <div id="edit-fossil-geochronology" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Geochronology</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <div class="form-group">
                        <label class="control-label">Time Interval</label>
                        <select id="edit-fossil-geochronology">
                        </select>
                    </div>
                </form>
            </div>
            <div class="edit-fossil-footer">
            </div>
        </div>
    </div>

    <?php
}
