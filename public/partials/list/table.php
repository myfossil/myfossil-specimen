<?php

use myFOSSIL\Plugin\Specimen\Fossil;

function myfossil_list_fossils_table( $fossils ) {
    $unk = '<span class="unknown">Unknown</span>';
    ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Taxon</th>
                    <th>Location</th>
                    <th>Geochronology</th>
                    <th>Lithostratigraphy</th>
                </tr>
            </thead>
            <tbody>
            <?php while ( $fossils->have_posts() ) : $fossils->the_post(); ?>
            <?php $fossil = new Fossil( get_the_id() ); ?>
                <tr class="hover-hand" data-href="/fossils/<?=get_the_id() ?>">
                    <td>
                        <div class="pull-left">
                            <img style="max-width: 75px" src="<?=$fossil->image ?>" class="img-responsive" />
                        </div>
                        <div class="pull-left" style="padding: 5px">
                            <span class="fossil-name" style="font-weight: bold; font-size: 1.2em; color: #000">
                                <?=$fossil->name ?>
                            </span>
                            <p class="author">
                                by <?=bp_core_get_userlink( $fossil->author->ID ) ?>
                            </p>
                        </div>
                    </td>
                    <td>
                        <?=$fossil->taxon ? $fossil->taxon : $unk ?>
                    </td>
                    <td>
                        <?=$fossil->location ? $fossil->location : $unk ?>
                    </td>
                    <td>
                        <?=$fossil->time_interval ? $fossil->time_interval : $unk ?>
                    </td>
                    <td>
                        <?php foreach ( array( 'group', 'formation', 'member' ) as $lith ): ?>
                            <span class="fossil-property col-xs-4"><?=ucfirst( $lith ) ?></span>
                            <?=( $fossil->strata && property_exists( $fossil->strata, $lith ) && $fossil->strata->{ $lith } ) ? $fossil->strata->{ $lith } : $unk ?>
                            <br />
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php
}