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
 * TimeInterval.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class TimeInterval extends Base 
{
    const CPT_NAME =  'time_interval';

    /**
     * TimeInterval.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    public function __construct( $post_id=null, $meta=null )
    {
        parent::__construct( $post_id, $meta );

        $this->pbdb = new PBDB\GeologicalTimeInterval;

        $this->_meta_keys = array( 'pbdb_id', 'parent_pbdb_id', 'color',
                'late_age_ma', 'early_age_ma', 'reference_id' );
    }

    // {{{ Custom Post Type
    public static function register_cpt() {
        $labels = array(
            'name'                => __( 'Time Intervals', 'myfossil-specimen' ),
            'singular_name'       => __( 'Time Interval', 'myfossil-specimen' ),
            'menu_name'           => __( 'Time Intervals', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Time Interval:', 'myfossil-specimen' ),
            'all_items'           => __( 'Time Intervals', 'myfossil-specimen' ),
            'view_item'           => __( 'View Time Interval', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Time Interval', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Time Interval', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Time Interval', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Time Interval', 'myfossil-specimen' ),
            'not_found'           => __( 'Time Interval not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Time Interval not found in Trash', 'myfossil-specimen' ),
        );

        $args = array(
            'label'               => __( 'myfs_time_interval', 'myfossil-specimen' ),
            'description'         => __( 'Represents a time interval', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'author', 'custom-fields',
                'comments' ),
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
            'menu_icon'           => 'dashicons-backup'
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
}