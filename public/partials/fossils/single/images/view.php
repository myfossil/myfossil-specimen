<?php

function fossil_view_images( $fossil ) {
    ?>
    <h3>Images</h3>
    <img class="img-responsive" src="<?=$fossil->image ?>" />
    <?php
}
