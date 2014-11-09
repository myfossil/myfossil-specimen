<?php

require_once( 'images/edit.php' );
require_once( 'images/view.php' );

function fossil_images( $fossil=null ) {
    fossil_view_images( $fossil );
    fossil_edit_images( $fossil );
}
