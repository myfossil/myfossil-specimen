<?php

function myfossil_fossil_render_single_settings( $fossil )
{
?>
    <div id="buddypress" class="container page-styling site-main" role="main">
    <h3>Settings</h3>

    <div class="col-xs-12 col-md-6">
        <h4>Status</h4>
        <div>
            <?php $draft = ( get_post_status( $fossil->id ) == 'draft' ); ?>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default btn-sm <?php echo ( $draft ) ? "btn-active active" : null ?>">
                    <input type="radio" name="status"
                        id="draft" autcomplete="off"
                        <?php echo ( $draft ) ? "checked" : null ?>
                        class="post_status" value="draft" />
                    <i class="fa fa-fw fa-eye-slash"></i>
                    Draft
                </label>
                <label class="btn btn-default btn-sm <?php echo ( $draft ) ? null : "btn-active active" ?>">
                    <input type="radio" name="status"
                        id="published" autcomplete="off"
                        <?php echo ( $draft ) ? null : "checked" ?>
                        class="post_status" value="publish" />
                    <i class="fa fa-fw fa-eye"></i>
                    Published
                </label>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <h4>Delete</h4>
        <div class="alert alert-danger">
            <button type="button" class="btn btn-default btn-block" id="delete-fossil">
                <i class="fa fa-fw fa-trash-o"></i>
                Delete
            </button>

            <p style="padding: 20px; text-align: center">
                This <strong>cannot be undone</strong>, so use caution!
            </p>
        </div>
    </div>
    </div>

    <?php
}
