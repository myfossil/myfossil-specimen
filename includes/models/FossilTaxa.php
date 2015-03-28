<?php
/**
 * ./includes/models/Taxa.php
 *
 * Taxa class, contains Taxons.
 *
 *
 * @link        https://github.com/myfossil
 * @since       0.5.0
 * @subpackage  myFOSSIL/includes
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 */


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
 * Taxon.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class FossilTaxa extends Base
{
    const POST_TYPE =  'myfossil_taxa';

    /**
     * Taxa.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @param unknown $post_id (optional)
     * @param unknown $meta    (optional)
     */
    public function __construct( $post_id=null, $meta=array() )
    {
        parent::__construct( $post_id, $meta );
        $this->_meta_keys = self::create_meta_keys();
    }

    public static function create_meta_keys() {
        $meta_keys = array();
        foreach ( self::get_ranks() as $k ) {
            $meta_keys[] = sprintf( 'taxon_id_%s', $k );
        }
        return $meta_keys;
    }

    public static function get_ranks() {
        return array( 'phylum', 'class', 'order', 'family', 'genus',
                'species');
    }

    /**
     *
     *
     * @param unknown $recursive (optional)
     * @return unknown
     */
    public function save( $recursive=false )
    {
        return parent::_save( self::POST_TYPE, $recursive );
    }

    // {{{ Custom Post Type

    /**
     */
    public static function register_cpt()
    {
        $args = array(
            'supports'            => array( 'custom-fields' ),
            'capability_type'     => 'post',
        );

        register_post_type( self::POST_TYPE, $args );
    }
    // }}}

    /**
     *
     *
     * @param unknown $key
     * @return unknown
     */
    public function __get( $key )
    {
        if ( in_array( $key, self::get_ranks(), true ) ) {
            return new Taxon( $this->{ sprintf( 'taxon_id_%s', $key ) } );
        }

        return parent::__get( $key );
    }

    public function __set( $key, $value )
    {
        $k = $key;
        if ( in_array( $key, self::get_ranks(), true ) ) {
            $k = sprintf( 'taxon_id_%s', $key );
        }

        return parent::__set( $k, $value );
    }
}
