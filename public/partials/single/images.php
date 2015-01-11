<?php

require_once 'information/images.php';

function myfossil_fossil_render_single_images_edit( $fossil, $image )
{
?>
    <?php if ( current_user_can( 'edit_post', $fossil->id ) ) : ?>
        <div class="text-center">
            <?php if ( (int) $fossil->image_id !== (int) $image->ID ) : ?>
            <a class="btn btn-default fossil-feature-image" data-attachment-id="<?php echo $image->ID ?>">
                Feature Image
            </a>
            <?php endif; ?>
            <a class="btn btn-default fossil-delete-image" data-attachment-id="<?php echo $image->ID ?>">
                Delete Image
            </a>
        </div>
    <?php endif;
}

function myfossil_fossil_render_single_images( $fossil )
{
    $images = get_attached_media( 'image', $fossil->id );

    if ( ! is_array( $images ) ) {
        $images = array( $images );
    }

?>
    <div id="buddypress" class="container page-styling site-main" role="main">
        <div class="row" style="margin-bottom: 20px;">
            <span class="btn btn-default btn-file" id="upload-button">
                Upload Images
                <input class="form-control" type="file" id="fossil-upload-image" multiple />
            </span>
        </div>
        <div class="row" id="fossil-images">
    <?php

    foreach ( $images as $image ) {
        $image_id = $image->ID;
        $image_src = wp_get_attachment_url( $image_id );
?>
            <div class="activity-entry col-sm-12 col-md-4">
                <div class="activity-body">
                    <img class="img-responsive"
                            src="<?php echo $image_src ?>" style="padding: 10px;"
                            data-attachment-id="<?php echo $image_id ?>" />
                </div>
                <div class="activity-footer">
                    <?php myfossil_fossil_render_single_images_edit( $fossil, $image ); ?>
                </div>
            </div>
        <?php
    }
?>
        </div>
    </div>
    <?php
}
