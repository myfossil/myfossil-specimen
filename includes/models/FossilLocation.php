<?php
/**
 * ./includes/models/FossilLocation.php
 *
 * FossilLocation class.
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 *
 * @link        https://github.com/myfossil
 * @since       0.0.1
 * @subpackage  myFOSSIL/includes
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
    const CPT_NAME =  'fossil_loc';
    
    public function __construct( $post_id=null, $meta=array() )
    {
        parent::__construct( $post_id, $meta );

        $this->_meta_keys = array( 'latitude', 'longitude', 'country', 'state',
                'county', 'city', 'zip', 'address', 'map_url');
    }

    // {{{ Custom Post Type
    public static function register_cpt() {
        $args = array(
            'supports'            => array( 'author', 'custom-fields', 'comments' ),
            'public'              => true,
            'show_ui'             => false,
            'rewrite'             => false,
        );

        register_post_type( self::PLUGIN_PREFIX . self::CPT_NAME, $args );
    }
    // }}}
}
