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
                'time_interval_id', 'stratum_formation_id', 'stratum_group_id',
                'stratum_member_id', 'dimension_id', 'reference_id' );
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

            case 'strata':
                if ( ! property_exists( $this->_cache, 'strata' ) )
                    $this->_cache->strata = new \stdClass;

                foreach ( Stratum::get_ranks() as $rank ) {
                    $stratum_key = sprintf( 'stratum_%s_id', $rank );

                    if ( ! $this->{ $stratum_key } ) 
                        continue;

                    if ( ! property_exists( $this->_cache->strata, $rank ) 
                            || $this->_cache->strata->{ $rank }->id !== $this->{ $stratum_key } )
                        $this->_cache->strata->{ $rank } = new Stratum( $this->{ $stratum_key } );
                }

                return $this->_cache->strata;
                    
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

            case 'image':
                if ( ! $this->id ) return;
                $_ = get_attached_media( 'image', $this->id );
                $m = array_pop( $_ );
                return $m->guid;
            default:
                return parent::__get( $key );
                break;

        }

        return null;
    }


    public static function load_defaults() {
        // {{{ Fossil data
        $data = array(
                'taxons' => array(
                        array( 'name' => 'Ostracoda', 'rank' => 'class' ),
                        array( 'name' => 'Salicaceae', 'rank' => 'family' ),
                        array( 'name' => 'Pecopteris', 'rank' => 'genus' ),
                    ),

                'locations' => array(
                        array( 
                            'latitude' => 38.8765,
                            'longitude' => -113.4678,
                            'state' => 'UT',
                            'county' => 'Millard'
                        ),
                        array( 
                            'state' => 'WY',
                        ),
                        array( 
                            'latitude' => 41.305,
                            'longitude' => -88.15,
                            'state' => 'IL',
                            'county' => 'Grundy'
                        ),
                    ),

                'strata' => array(
                        array(
                            'name' => 'Kanosh Shale',
                            'level' => 'formation'
                        ),
                        null,
                        array(
                            'name' => 'Francis Creek Shale',
                            'level' => 'formation'
                        ),
                    ),

                'time_intervals' => array(
                        array( 'name' => 'Middle Ordovician', 'level' => 'age', 'color' => '#4DB47E' ),
                        array( 'name' => 'Eocene', 'level' => 'age', 'color' => '#FDB46C' ),
                        array( 'name' => 'Pennsylvanian', 'level' => 'age', 'color' => '#99C2B5' ),
                    ),

                'media' => array(
                        array( 'Ostracoda.jpg' ),
                        array( 'Willow.jpg' ),
                        array( 'Mazon Creek_1.jpg', 'Mazon Creek_2.jpg' )
                    )
            );
        // }}}

        $obj_ids = array();
        foreach ( array( 'taxon', 'location', 'stratum', 'time_interval', 'media' ) as $k )
            $obj_ids[$k] = array();

        foreach ( $data as $obj_type => $obj_data ) {
            foreach ( $obj_data as $obj_datum ) {
                switch ( $obj_type ) {
                    case 'taxons':
                        $obj = new Taxon( null, $obj_datum );
                        $obj->save();
                        wp_publish_post( $obj->id );
                        array_push( $obj_ids['taxon'], $obj->id );
                        break;
                    case 'locations':
                        $obj = new FossilLocation( null, $obj_datum );
                        $obj->save();
                        wp_publish_post( $obj->id );
                        array_push( $obj_ids['location'], $obj->id );
                        break;
                    case 'strata':
                        $obj = new Stratum( null, $obj_datum );
                        $obj->save();
                        wp_publish_post( $obj->id );
                        array_push( $obj_ids['stratum'], $obj->id );
                        break;
                    case 'time_intervals':
                        $obj = new TimeInterval( null, $obj_datum );
                        $obj->save();
                        wp_publish_post( $obj->id );
                        array_push( $obj_ids['time_interval'], $obj->id );
                        break;
                }
            }
        }

        for ( $i = 0; $i < 3; $i++ ) {
            $fossil = new Fossil( null, 
                    array(
                        'taxon_id'         => $obj_ids['taxon'][$i],
                        'location_id'      => $obj_ids['location'][$i],
                        'stratum_id'       => $obj_ids['stratum'][$i],
                        'time_interval_id' => $obj_ids['time_interval'][$i]
                    )
                );

            $fid = $fossil->save();
            wp_publish_post( $fid );

            /* Add media */
            foreach( $data['media'][$i] as $filename ) {
                $att = array(
                        'guid' => plugins_url( 'myfossil-specimen/admin/data/media/' . $filename ),
                        'post_mime_type' => wp_check_filetype( $filename, null )['type'],
                        'post_title' => $filename,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                $att_id = wp_insert_attachment( $att, $filename, $fid );

                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $att_dat = wp_generate_attachment_metadata( $att_id, $filename );
                wp_update_attachment_metadata( $att_id, $att_dat );
            }
        }

        return true;
    }

}
