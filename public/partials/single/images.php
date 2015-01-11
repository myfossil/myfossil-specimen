<?php

require_once 'information/images.php';

function myfossil_fossil_render_single_images( $fossil )
{
    $images = get_attached_media( 'image', $fossil->id );

    if ( ! is_array( $images ) ) {
        $images = array( $images );
    } 

    ?> 
        <div class="row"> 
    <?php

    foreach ( $images as $image ) {
        $image_id = $image->ID;
        $image_src = wp_get_attachment_url( $image_id );
        ?>
            <div class="activity-entry col-sm-12 col-md-4">
                <div class="activity-body">
                    <img class="img-responsive fossil-image"
                            src="<?=$image_src ?>" style="padding: 10px;"
                            data-attachment-id="<?=$image_id ?>" />
                </div>
            </div>
        <?php
    }
    ?> 
        </div>
    <?php
}
