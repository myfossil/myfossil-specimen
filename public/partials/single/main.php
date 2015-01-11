<?php

require_once( 'main/images.php' );
require_once( 'main/comments.php' );
require_once( 'main/dimensions.php' );
require_once( 'main/geochronology.php' );
require_once( 'main/lithostratigraphy.php' );
require_once( 'main/location.php' );
require_once( 'main/taxonomy.php' );

function myfossil_fossil_render_single_main( $fossil ) {
    ?>
        <div class="row clearfix">

            <!-- Classification -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8">
                <?php fossil_taxonomy( $fossil ); ?>
                <?php fossil_dimensions( $fossil ); ?>
            </div>

            <!-- Image(s) -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                <?php fossil_images( $fossil ); ?>
            </div>

        </div>


        <div class="row">
        
            <!-- Location -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php fossil_location( $fossil ); ?>
            </div>

        </div>


        <div class="row">
        
            <!-- Geochronology -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?php fossil_geochronology( $fossil ); ?>
            </div>

            <!-- Lithostratigraphy -->
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?php fossil_lithostratigraphy( $fossil ); ?>
            </div>

        </div>


        <?php if ( comments_open() || '0' != get_comments_number() ): ?>
        <div class="row">

            <!-- Comments -->
            <div class="col-lg-12">
                <?php fossil_comments( $fossil ); ?>
            </div>
        </div>
        <?php endif; ?>

    <?php
}
