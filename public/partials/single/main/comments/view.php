<?php

function fossil_view_comments( $fossil=null ) {
    ?>

    <h3>Comments</h3>
    <?php // bp_activity_comments() ?>
    <h1><?=$fossil->query_vars['fossil_id'] ?></h1>    
    <?php
}
