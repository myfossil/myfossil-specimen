<?php
namespace myFOSSIL\Plugin\Specimen;

/**
 * PaleoBio Database (PBDB) objects.
 *
 * These objects serve as a basis for all paleontological objects in myFOSSIL,
 * so using the myFOSSIL\PBDB library makes sense here.
 *
 * @since   0.0.1
 * @see     {@link https://github.com/myfossil/pbdb-php}
 */
use myFOSSIL\PBDB;

/**
 * FossilDimension.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class FossilDimension extends Base
{
    const POST_TYPE = 'myfossil_fossil_dim';

    /**
     * FossilDimension.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    public function __construct( $post_id=null, $args=array() )
    {
        parent::__construct( $post_id, $args );

        $this->_meta_keys = array( 'length_meters', 'width_meters',
                'height_meters');
    }

    public function save( $recursive=false ) {
        return parent::_save( self::POST_TYPE, $recursive );
    }

    // {{{ Custom Post Type
    public static function register_cpt() {
        $args = array(
            'supports'            => array( 'author', 'custom-fields', 'comments' ),
            'public'              => true,
            'show_ui'             => false,
            'rewrite'             => false,
        );

        register_post_type( self::POST_TYPE, $args );
    }
    // }}}

    public function __get( $key ) {
        if ( in_array( $key, array( 'length', 'width', 'height' ) ) )
            $key = $key . '_meters';
        return parent::__get( $key );
    }

    public function __set( $key, $value ) {
        if ( in_array( $key, array( 'length', 'width', 'height' ) ) )
            $key = $key . '_meters';
        return parent::__set( $key, $value );
    }

    public function as_cm( $key ) {
        return $this->$key * 100.;
    }
}
