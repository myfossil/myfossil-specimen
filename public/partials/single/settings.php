<?php

function myfossil_fossil_render_single_settings( $fossil ) {
    ?>

    <dt>Status</dt>
    <dd>
        <?php $draft = ( get_post_status( $fossil->id ) == 'draft' ); ?>
        <div class="btn-group" data-toggle="buttons">
            <label class="btn btn-default btn-sm <?=( $draft ) ? "btn-active active" : null ?>">
                <input type="radio" name="status" 
                    id="draft" autcomplete="off" 
                    <?=( $draft ) ? "checked" : null ?>
                    class="post_status" value="draft" />
                <i class="fa fa-fw fa-eye-slash"></i>
                <span class="sr-only">Draft</span>
            </label>
            <label class="btn btn-default btn-sm <?=( $draft ) ? null : "btn-active active" ?>">
                <input type="radio" name="status" 
                    id="published" autcomplete="off"
                    <?=( $draft ) ? null : "checked" ?>
                    class="post_status" value="publish" />
                <i class="fa fa-fw fa-eye"></i>
                <span class="sr-only">Published</span>
            </label>
        </div>
    </dd>

    <?php
}
