<?php

function fossil_edit_geochronology( $fossil=null ) {
    ?>

    <input type="hidden" id="fossil-geochronology-name" />
    <input type="hidden" id="fossil-geochronology-level" />
    <input type="hidden" id="fossil-geochronology-pbdb" />
    <input type="hidden" id="fossil-geochronology-color" />

    <div id="edit-fossil-geochronology" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Geochronology</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <div class="form-group">
                        <label class="control-label">Time Interval</label>
                        <select class="form-control" id="edit-fossil-geochronology">
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
