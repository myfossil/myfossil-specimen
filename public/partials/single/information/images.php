<?php

require_once '_common.php';

function fossil_view_images( $fossil )
{
    $images = get_attached_media( 'image', $fossil->id );
    $featured_image = array_pop( $images );
    if ( $featured_image && property_exists( $featured_image, 'ID' ) ) {
        $image_id = $featured_image->ID;
        $image_src = wp_get_attachment_url( $featured_image->ID );
    } else {
        $image_id = 0;
        $image_src = null;
    }
    ?>
    <h3 class="sr-only">Image</h3>
    <div class="activity-entry">
        <div class="activity-body">
            <img id="fossil-featured-image" class="img-responsive fossil-image"
                    src="<?php echo $image_src ?>" style="padding: 10px;"
                    data-attachment-id="<?php echo $image_id ?>" />
        </div>
    </div>
    <?php
}

function fossil_images( $fossil=null )
{
    fossil_view_images( $fossil );
}
