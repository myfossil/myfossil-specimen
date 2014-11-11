<?php
/**
 * ./includes/models/Taxon.php
 *
 * Taxon class.
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
class Taxon extends Base
{
    const POST_TYPE =  'myfossil_taxon';

    /**
     * Taxon.
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

        $this->_meta_keys = array( 'pbdb_id', 'parent_pbdb_id', 'common_name',
            'rank', 'reference_id' );

        $this->pbdb = new PBDB\Taxon;
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
     * {{{ Custom Post Type
     */
    public static function register_cpt()
    {
        $labels = array(
            'name'                => __( 'Taxa', 'myfossil-specimen' ),
            'singular_name'       => __( 'Taxon', 'myfossil-specimen' ),
            'menu_name'           => __( 'Taxa', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Taxon:', 'myfossil-specimen' ),
            'all_items'           => __( 'Taxa', 'myfossil-specimen' ),
            'view_item'           => __( 'View Taxon', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Taxon', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Taxon', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Taxon', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Taxon', 'myfossil-specimen' ),
            'not_found'           => __( 'Taxon not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Taxon not found in Trash', 'myfossil-specimen' ),
        );

        $args = array(
            'label'               => __( self::POST_TYPE, 'myfossil-specimen' ),
            'description'         => __( 'Biological classification
                representations (taxa)', 'myfossil-specimen' ),
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
            'menu_icon'           => 'dashicons-editor-ul'
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
        if ( $key == 'reference' ) {
            if ( $this->reference_id ) {
                $this->_cache->reference = new Reference( $this->reference_id );
                return $this->_cache->reference;
            }
        }

        return parent::__get( $key );
    }

    /**
     *
     *
     * @param unknown $action
     * @param unknown $activity
     * @return unknown
     */
    public static function bp_format_activity( $action, $activity )
    {
        return parent::bp_format_activity( $action, $activity );

        if ( $activity->content ) {
            $from = new Taxon;
            $to = new Taxon;
            foreach ( json_decode( $activity->content ) as $ch_set ) {
                $from->{ $ch_set->key } = $ch_set->from;
                $to->{ $ch_set->key } = $ch_set->to;
            }

            $activity->content = sprintf( 'Changed Taxon from <span
                    class="border">%s</span> to <span class="border">
                    %s</span>', $from,
                $to );

            unset( $from );
            unset( $to );
        }

        return parent::bp_format_activity( $action, $activity );
    }

    /**
     *
     *
     * @return unknown
     */
    public function __toString()
    {
        $colors = array(
            "life"    => "#777fff",
            "domain"  => "#77c3ff",
            "kingdom" => "#58fff7",
            "phylum"  => "#58ffa5",
            "class"   => "#5dff58",
            "order"   => "#b0ff58",
            "family"  => "#fffd58",
            "genus"   => "#ffaa58",
            "species" => "#e28d54"
        );

        $bgcolor = array_key_exists( $this->rank, $colors ) ?
            $colors[$this->rank] : '#eee';

        return sprintf(
            '<span class="label" style="background-color: %s; color: #333">%s</span> %s',
            $bgcolor, $this->rank, $this->name );
    }
}
