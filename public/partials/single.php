<?php
use myFOSSIL\Plugin\Specimen\Fossil;

function myfossil_fossil_render_single( $fossil_id, $view ) {
    $fossil = new Fossil( $fossil_id );

    ?>
    <div id="fossil" class="content-area">
        <?php fossil_header( $fossil, $view ); ?>
        <div id="buddypress" class="container page-styling site-main" role="main">

    <?php 
    switch ( $view ) {
        case 'main':
            myfossil_fossil_render_single_main( $fossil );
            break;
        case 'history':
            break;
        case 'discussion':
            break;
    }
    ?>

        </div>
    </div>

    <?php
}
