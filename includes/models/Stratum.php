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
 * Stratum.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class Stratum extends Base
{

    const CPT_NAME =  'stratum';

    /**
     * Stratum.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    public function __construct( $post_id=null, $args=array() )
    {
        parent::__construct( $post_id, $args );

        $this->_meta_keys = array( 'pbdb_id', 'parent_pbdb_id', 'color', 'level',
                'reference_id' );
    }

    public function save( $recursive=false ) {
        return parent::_save( self::PLUGIN_PREFIX . self::CPT_NAME, $recursive );
    }

    // {{{ Custom Post Type
    public static function register_cpt() {
        $labels = array(
            'name'                => __( 'Strata', 'myfossil-specimen' ),
            'singular_name'       => __( 'Stratum', 'myfossil-specimen' ),
            'menu_name'           => __( 'Strata', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Stratum:', 'myfossil-specimen' ),
            'all_items'           => __( 'Strata', 'myfossil-specimen' ),
            'view_item'           => __( 'View Stratum', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Stratum', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Stratum', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Stratum', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Stratum', 'myfossil-specimen' ),
            'not_found'           => __( 'Stratum not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Stratum not found in Trash', 'myfossil-specimen' ),
        );

        $args = array(
            'label'               => __( self::PLUGIN_PREFIX . self::CPT_NAME, 'myfossil-specimen' ),
            'description'         => __( 'Represents a geological stratum', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'author', 'thumbnail',
                'custom-fields', 'comments' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-tagcloud'
        );

        register_post_type( self::PLUGIN_PREFIX . self::CPT_NAME, $args );
    }
    // }}}

    public function __get( $key ) {
        if ( $key == 'reference' ) {
            if ( $this->reference_id ) {
                $this->_cache->reference = new Reference( $this->reference_id );
                return $this->_cache->reference;
            }
        }

        return parent::__get( $key );
    }

    public function __toString() {
        if ( $this->level && $this->name )
            return sprintf( 
                "<span class=\"label label-primary\">%s</span> %s", 
                $this->level, $this->name );
        else
            return sprintf( "<span class=\"label
                    label-default\">Unknown</span>" );
    }
}
