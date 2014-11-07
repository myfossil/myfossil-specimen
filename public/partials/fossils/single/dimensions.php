<?php

require_once( 'dimensions/edit.php' );
require_once( 'dimensions/view.php' );

function fossil_dimensions( $fossil=null ) {
    fossil_view_dimensions( $fossil );
    fossil_edit_dimensions( $fossil );
}
