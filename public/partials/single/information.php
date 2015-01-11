<?php

require_once 'information/images.php';
require_once 'information/comments.php';
require_once 'information/dimensions.php';
require_once 'information/geochronology.php';
require_once 'information/lithostratigraphy.php';
require_once 'information/location.php';
require_once 'information/taxonomy.php';

function myfossil_fossil_render_single_information( $fossil )
{
?>
    <div id="buddypress" class="container page-styling site-main" role="main">
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
    </div>


    <div class="container">
        <?php if ( comments_open() || '0' != get_comments_number() ): ?>
        <div class="row">

            <!-- Comments -->
            <div class="col-lg-12">
                <?php fossil_comments( $fossil ); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php
}
