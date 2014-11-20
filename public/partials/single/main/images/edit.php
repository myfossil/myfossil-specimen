<?php
function fossil_edit_images( $fossil ) {
    ?>
    <?php if ( current_user_can( 'edit_post', $fossil->id ) ) : ?>
    <div class="text-center">
        <span class="btn btn-default btn-file">
            Select Image 
            <input class="form-control" type="file" id="fossil-upload-image" />
        </span>
        <a class="btn btn-default" id="fossil-delete-image">
            Delete Image 
        </a>
    </div>
    <?php
    endif;
}


