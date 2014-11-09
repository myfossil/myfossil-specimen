<?php
function fossil_edit_images( $fossil ) {
    ?>
    <style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    </style>

    <form class="form" enctype="multipart/form-data">
        <span class="btn btn-default btn-file">
            Select Image <input class="form-control" type="file" />
        </span>
        <button type="submit" class="btn btn-default btn-file">
            Upload
        </button>
    </form>
    <?php
}


function upload_user_file( $file=array() )
{
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );

    $file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

    if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        $filename = $file_return['file'];

        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );

        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );

        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        if ( 0 < intval( $attachment_id ) ) {
            return $attachment_id;
        }
    }

    return false;
}
