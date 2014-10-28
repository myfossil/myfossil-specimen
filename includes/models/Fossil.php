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
 * Fossil.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class Fossil extends Base
{
    const CPT_NAME = 'fossil';

    /**
     * Fossil.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    public function __construct( $post_id=null, $args=array() )
    {
        parent::__construct( $post_id, $args );

        $this->pbdb = new PBDB\FossilOccurence;

        $this->_meta_keys = array( 'pbdb_id', 'taxon_id', 'location_id',
                'time_interval_id', 'stratum_id', 'dimension_id',
                'reference_id' );
    }

    public function save( $recursive=false ) {
        return parent::_save( self::PLUGIN_PREFIX . self::CPT_NAME, $recursive );
    }

    // {{{ Custom Post Type
    public static function register_cpt() {
        $labels = array(
            'name'                => __( 'Fossils', 'myfossil-specimen' ),
            'singular_name'       => __( 'Fossil', 'myfossil-specimen' ),
            'menu_name'           => __( 'Fossils', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Fossil:', 'myfossil-specimen' ),
            'all_items'           => __( 'Fossils', 'myfossil-specimen' ),
            'view_item'           => __( 'View Fossil', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Fossil', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Fossil', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Fossil', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Fossil', 'myfossil-specimen' ),
            'not_found'           => __( 'Fossil not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Fossil not found in Trash', 'myfossil-specimen' ),
        );

        $args = array(
            'label'               => __( self::PLUGIN_PREFIX . self::CPT_NAME, 'myfossil-specimen' ),
            'description'         => __( 'Represents a fossil', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author',
                'thumbnail', 'custom-fields', 'comments', 'revisions',
                'post-formats' ),
            'hierarchical'        => false,
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
            'menu_icon'           => 'dashicons-carrot'
        );

        register_post_type( self::PLUGIN_PREFIX . self::CPT_NAME, $args );
    }
    // }}}

    /**
     * Custom getters specific to Fossil's
     *
     * @todo    Break up swith statements into private methods
     * @since   0.0.1
     * @access  public
     * @param unknown $key
     * @return unknown
     */
    public function __get( $key )
    {
        if ( property_exists( $this->_cache, $key ) )
            return $this->_cache->{$key};

        switch ( $key ) {
            case 'taxon':
                if ( $this->taxon_id ) {
                    $this->_cache->taxon = new Taxon( $this->taxon_id );
                    return $this->_cache->taxon;
                }
                break;

            case 'location':
                if ( $this->location_id ) {
                    $this->_cache->location = new FossilLocation( $this->location_id );
                    return $this->_cache->location;
                }
                break;

            case 'time':
            case 'time_interval':
            case 'geochronology':
                if ( $this->time_interval_id ) {
                    $this->_cache->time_interval = new TimeInterval( $this->time_interval_id );
                    return $this->_cache->time_interval;
                }
                break;

            case 'stratum':
            case 'lithostratigraphy':
                if ( $this->stratum_id ) {
                    $this->_cache->stratum = new Stratum( $this->stratum_id );
                    return $this->_cache->stratum;
                }
                break;

            case 'dim':
            case 'dimension':
                if ( $this->dimension_id ) {
                    $this->_cache->dimension = new FossilDimension( $this->dimension_id );
                    return $this->_cache->dimension;
                }
                break;

            case 'reference':
                if ( $this->reference_id ) {
                    $this->_cache->reference = new Reference( $this->reference_id );
                    return $this->_cache->reference;
                }
                break;

            case 'history':
                return null;
                break;

            default:
                return parent::__get( $key );
                break;

        }

        return null;
    }


    public static function load_defaults() {
        $taxon = new Taxon( null, 
                array( 'name' => 'Ostracoda', 'rank' => 'class' ) );
        $taxon->save();

        $location = new FossilLocation( null, 
                array( 
                    'latitude' => 38.8765,
                    'longitude' => -113.4678,
                    'state' => 'UT',
                    'county' => 'Millard'
                ) 
            );
        $location->save();

        $stratum = new Stratum( null, 
                array(
                    'name' => 'Kanosh Shale',
                    'level' => 'formation'
                )
            );
        $stratum->save();

        $time_interval = new TimeInterval( null, 
                array( 'name' => 'Middle Ordovician', 'level' => 'age' ) );

        $time_interval->save();

        $fossil = new Fossil( null,
                array(
                    'taxon_id' => $taxon->id,
                    'location_id' => $location->id,
                    'stratum_id' => $stratum->id,
                    'time_interval_id' => $time_interval->id
                )
            );
        $fossil->save();

        // Write a few more times so that we have data...
        $fossil->wp_post = null; $fossil->save();
        $fossil->wp_post = null; $fossil->save();
        $fossil->wp_post = null; $fossil->save();
        $fossil->wp_post = null; $fossil->save();

        return true;
    }

}
