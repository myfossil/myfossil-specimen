<?php
use myFOSSIL\Plugin\Specimen\Fossil;

function myfossil_fossil_render_single( $fossil_id, $view ) {
    $fossil = new Fossil( $fossil_id );

    ?>
    <div id="fossil" class="content-area">
        <?php 
        // from ./single/header.php
        fossil_header( $fossil, $view ); 
        ?>
        <div id="buddypress" class="container page-styling site-main" role="main">

    <?php 
    switch ( $view ) {
        case 'main':
            myfossil_fossil_render_single_main( $fossil );
            break;
        case 'history':
            myfossil_fossil_render_single_history( $fossil );
            break;
        case 'discussion':
            myfossil_fossil_render_single_discussion( $fossil );
            break;
        case 'images':
            myfossil_fossil_render_single_images( $fossil );
            break;
        case 'settings':
            myfossil_fossil_render_single_settings( $fossil );
            break;
    }
    ?>

        </div>
    </div>

    <?php
}
