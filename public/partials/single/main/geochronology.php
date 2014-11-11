<?php

require_once( 'geochronology/view.php' );
require_once( 'geochronology/edit.php' );

function fossil_geochronology( $fossil=null ) {
    fossil_view_geochronology( $fossil );
    fossil_edit_geochronology( $fossil );
}
