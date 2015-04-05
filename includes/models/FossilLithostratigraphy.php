<?php
/**
 * ./includes/models/FossilLithostratigraphy.php
 *
 * @link        https://github.com/myfossil
 * @since       0.5.0
 * @subpackage  myFOSSIL/includes
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 */


namespace myFOSSIL\Plugin\Specimen;

/**
 * FossilLithostratigraphy
 *
 * @since      0.5.0
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class FossilLithostratigraphy extends Base
{
    const POST_TYPE =  'myfossil_geochrons';

    public function __construct( $post_id=null, $meta=array() )
    {
        parent::__construct( $post_id, $meta );
        $this->_meta_keys = self::create_meta_keys();
    }

    public static function create_meta_keys()
    {
        $meta_keys = array();
        foreach ( self::get_ranks() as $k ) {
            $meta_keys[] = sprintf( 'stratum_id_%s', $k );
        }
        return $meta_keys;
    }

    public static function get_ranks()
    {
        return array( 'group', 'formation', 'member' );
    }

    public function save( $recursive=false )
    {
        return parent::_save( self::POST_TYPE, $recursive );
    }

    public static function register_cpt()
    {
        $args = array(
            'supports'            => array( 'custom-fields' ),
            'capability_type'     => 'post',
        );

        register_post_type( self::POST_TYPE, $args );
    }

    public function __get( $key )
    {
        $is_stratum = false;
        foreach ( self::get_ranks() as $rank ) {
            if ( $rank == $key ) {
                $is_stratum = true;
            }
        }
        if ( $is_stratum ) {
            return new Stratum(
                $this->{ sprintf( 'stratum_id_%s', $key ) }
            );
        }
        return parent::__get( $key );
    }

    public function __set( $key, $value )
    {
        $k = $key;
        $is_stratum = false;
        foreach ( self::get_ranks() as $rank ) {
            if ( $rank == $key ) {
                $is_stratum = true;
            }
        }
        if ( $is_stratum ) {
            $k = sprintf( 'stratum_id_%s', $key );
        }
        return parent::__set( $k, $value );
    }

}
