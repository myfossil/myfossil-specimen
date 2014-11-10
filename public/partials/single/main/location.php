<?php

require_once( 'location/view.php');
require_once( 'location/edit.php');

function fossil_location( $fossil=null ) {
    fossil_view_location( $fossil );
    fossil_edit_location( $fossil );
}
