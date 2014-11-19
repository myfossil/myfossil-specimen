<?php

use myFOSSIL\Plugin\Specimen\Fossil;

function myfossil_list_fossils_table( $fossils ) {
    ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Thumbnail</th>
                    <th>Location</th>
                    <th>Taxon</th>
                    <th>Geochronology</th>
                    <th>Lithostratigraphy</th>
                </tr>
            </thead>
            <tbody>
            <?php while ( $fossils->have_posts() ) : $fossils->the_post(); ?>
            <?php $fossil = new Fossil( get_the_id() ); ?>
                <tr class="hover-hand" data-href="/fossils/<?=get_the_id() ?>">
                    <td>
                        <?=get_avatar( get_the_author_meta( 'ID' ), 50 ); ?>
                    </td>
                    <td>
                        <img style="max-width: 75px" src="<?=$fossil->image ?>" class="img-responsive" />
                    </td>
                    <td>
                        <?=$fossil->location ?>
                    </td>
                    <td>
                        <?=$fossil->taxon ?>
                    </td>
                    <td>
                        <?=$fossil->time_interval ?>
                    </td>
                    <td>
                        <?=$fossil->stratum ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php
}
