<?php

function fossil_view_images( $fossil ) {
    $images = get_attached_media( 'image', $fossil->id );
    $featured_image = array_pop( $images );
    $image_src = wp_get_attachment_url( $featured_image->ID );
    ?>
    <h3>Images</h3>
    <img id="fossil-featured-image" class="img-responsive" 
            src="<?php echo $image_src ?>" style="max-width:100%;" />
    <?php if ( is_array( $images ) ): ?>
        <div class="fossil-images-small">
        <?php foreach ( $images as $image_post ): ?>
            <img src="<?=wp_get_attachment_url( $image_post->ID ) ?>" class="col-lg-3 img-responsive" />
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php
}
