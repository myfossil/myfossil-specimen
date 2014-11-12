<?php
/**
 * ./includes/models/FossilLocation.php
 *
 * FossilLocation class.
 *
 *
 * @link        https://github.com/myfossil
 * @since       0.0.1
 * @subpackage  myFOSSIL/includes
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 */


namespace myFOSSIL\Plugin\Specimen;

/**
 * FossilLocation.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class FossilLocation extends Base
{
    const POST_TYPE = 'myfossil_fossil_loc';

    /**
     *
     *
     * @param unknown $post_id (optional)
     * @param unknown $meta    (optional)
     */
    public function __construct( $post_id=null, $meta=array() )
    {
        parent::__construct( $post_id, $meta );

        $this->_meta_keys = array( 'latitude', 'longitude', 'country', 'state',
            'county', 'city', 'zip', 'address', 'map_url' );
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

    /**
     *
     *
     * @return unknown
     */
    public function __toString()
    {
        if ( $this->city && $this->state )
            return sprintf( "%s, %s\n", $this->city, $this->state );
        if ( $this->county && $this->state )
            return sprintf( "%s, %s\n", $this->county, $this->state );
        if ( $this->state && $this->country )
            return sprintf( "%s, %s\n", $this->state, $this->country );
        foreach ( array( 'country', 'state', 'county', 'city' ) as $k )
            if ( $this->{$k} )
            return (string) $this->{$k};
        return (string) null;
    }

    // {{{ Custom Post Type

    /**
     */
    public static function register_cpt()
    {
        $args = array(
            'supports'            => array( 'author', 'custom-fields', 'comments' ),
            'public'              => true,
            'show_ui'             => false,
            'rewrite'             => false,
        );

        register_post_type( self::POST_TYPE, $args );
    }
    // }}}
}
