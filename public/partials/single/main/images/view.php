<?php

function fossil_view_images( $fossil ) {
    $images = get_attached_media( 'image', $fossil->id );
    $featured_image = array_pop( $images );
    $image_src = wp_get_attachment_url( $featured_image->ID );
    ?>
    <h3 class="sr-only">Image</h3>
    <div class="activity-entry">
        <div class="activity-body">
            <img id="fossil-featured-image" class="img-responsive fossil-image"
                    src="<?php echo $image_src ?>" style="padding: 10px;" 
                    data-attachment-id="<?=$featured_image->ID ?>" />
        </div>
    </div>
    <?php
    /*
    <?php if ( is_array( $images ) ): ?>
        <div class="fossil-images-small">
        <?php foreach ( $images as $image_post ): ?>
            <img src="<?=wp_get_attachment_url( $image_post->ID ) ?>" class="col-lg-3 img-responsive" />
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php
    */
}
