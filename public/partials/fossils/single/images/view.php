<?php

function fossil_view_images( $fossil ) {
    $image_id = get_post_meta( $fossil->id, '_image_id', true );
    $image_src = wp_get_attachment_url( $image_id );
    ?>
    <h3>Images</h3>
    <img id="fossil-featured-image" class="img-responsive" 
            src="<?php echo $image_src ?>" style="max-width:100%;" />
    <?php
}
