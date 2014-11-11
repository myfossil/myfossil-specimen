<?php

require_once( 'taxonomy/view.php' );
require_once( 'taxonomy/edit.php' );

function fossil_taxonomy( $fossil=null ) {
    fossil_view_taxonomy( $fossil );
    fossil_edit_taxonomy( $fossil );
}
