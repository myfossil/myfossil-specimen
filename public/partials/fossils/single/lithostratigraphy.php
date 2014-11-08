<?php

require_once( 'lithostratigraphy/view.php' );
require_once( 'lithostratigraphy/edit.php' );

function fossil_lithostratigraphy( $fossil=null ) {
    fossil_view_lithostratigraphy( $fossil );
    fossil_edit_lithostratigraphy( $fossil );
}
