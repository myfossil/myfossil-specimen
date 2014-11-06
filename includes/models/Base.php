<?php
/**
 * ./includes/models/Base.php
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
class Base
{
    /**
     * Namespace of this plugin.
     */
    const PLUGIN_PREFIX = 'myfs_';
    const CPT_NAME = null;

    /**
     * PBDB query object for this class.
     */
    public $pbdb;

    /**
     * WordPress post object (since WordPress decided to make WP_Post final).
     */
    public $wp_post;

    /**
     * metadata properties.
     */
    protected $_meta;
    protected $_meta_keys;
    protected $_cache;

    /**
     * Create Base class.
     */
    public function __construct( $post_id=null, $meta=array() )
    {
        $this->_meta = new \stdClass;
        $this->_cache = new \stdClass;

        /* Load the post, if defined */
        if ( $post_id )
            $this->wp_post = get_post( $post_id );

        /* Load metadata from WordPress */
        if ( $this->wp_post && $this->wp_post->ID )
            foreach ( get_post_custom( $this->wp_post->ID ) as $k => $v )
                if ( is_array( $v ) )
                    if ( count( $v ) == 1 )
                        $this->_meta->{$k} = array_pop( $v );
                    else
                        $this->_meta->{$k} = $v;
                else
                    $this->_meta->{$k} = $v;


        /* Load additional data, overwriting if defined */
        if ( is_array( $meta ) && count( $meta ) > 0 )
            foreach ( $meta as $k => $v )
                $this->_meta->{$k} = $v;
    }

    /**
     * Custom getter for properties that span multiple data sources.
     *
     * First attempts to get a local property, then tries the property private
     * array, then tries WordPress metadata, then tries PBDB, and then gives
     * up (returns null).
     *
     * Object Property => Object Meta Properties => WordPress => PBDB
     *
     * @since   0.0.1
     * @access  public
     * @param   string $key
     * @return  mixed    value of the property, null if not found.
     */
    public function __get( $key )
    {
        if ( $key == 'name' && $this->wp_post && $this->wp_post->post_title )
            return $this->wp_post->post_title;

        if ( $key == 'author' ) {
            if ( ! $this->wp_post->post_author ) return;
            $u = new \WP_User( $this->wp_post->post_author );
            return $u;
        }

        if ( $key == 'created_at' || $key == 'updated_at' ) {
            if ( ! $this->wp_post->post_author ) return;
            return $this->wp_post->post_date;
        }

        if ( ( $key == 'post_id' || $key == 'id' ) && $this->wp_post &&
                $this->wp_post->ID )
            return $this->wp_post->ID;

        /* Try local property */
        if ( property_exists( $this, $key ) )
            return $this->$key;

        /* Try local metadata */
        if ( property_exists( $this->_meta, $key ) )
            return $this->_meta->{$key};

        /* Try WordPress */
        if ( $this->wp_post->ID && $this->wp_post->{$key} )
            return $this->wp_post->{$key};

        /* Try PBDB */
        if ( $this->pbdb && property_exists( $this->pbdb, $key ) )
            return $this->pbdb->{$key};

        return; // null
    }

    /**
     * Custom setter for properties that span multiple data sources.
     *
     * @since   0.0.1
     * @access  public
     * @param string $key
     * @param mixed $value
     */
    public function __set( $key, $value )
    {
        if ( $key == 'name' )
            if ( $this->wp_post )
                $this->wp_post->post_title = $value;
            else
                $this->_meta->name = $value;

        /* Special cases when setting the PBDB ID */
        if ( $this->pbdb )
            if ( $key == 'pbdbid' || $key == 'pbdb_id' )
                $this->pbdb->pbdbid = $value;
            elseif ( $key == 'parent_pbdb_id' || $key == 'parent_pbdbid' )
                $this->pbdb->parent_no = $value;

        /* Set local properties of Object */
        if ( property_exists( $this, $key ) ) {
            $this->{$key} = $value;
        } else {
            $this->_meta->{$key} = $value;
        }
    }

    /**
     * Update or create new object in the database.
     *
     * If this object does not currently exist in the database, identified by
     * $this->id, then a new object will be created in the database and the new
     * id set to $this->id, otherwise overwrite all properties of the object
     * and update the last modified column.
     *
     * @todo    Add WordPress hook(s)
     *
     * @since   0.0.1
     * @access  public
     * @param   bool    $recursive       (optional) Recurse saving of children objects as well, default false.
     * @return  bool                                True upon success, false upon failure.
     */
    public function _save( $post_type, $recursive=false )
    {
        /* Update or create new Post */
        if ( $this->wp_post && $this->wp_post->ID ) {
            $this->wp_post->ID = wp_insert_post( $this->wp_post );
        } else {
            $post_title = property_exists( $this->_meta, 'name' ) ? $this->_meta->name : null;
            $post_args = array( 
                    'post_type' => $post_type,
                    'post_title' => $post_title
                );
            $post_id = wp_insert_post( $post_args );
            $this->wp_post = get_post( $post_id );
        }

        /* Save children objects as well, if asked to */
        if ( $this->_cache ) {
            foreach ( $this->_cache as $cache_key => $cached_object ) {
                if ( $recursive && method_exists( $cached_object, 'save' ) )
                    $cached_object->save();
                $this->_meta->{$cache_key . '_id'} = $cached_object->wp_post->ID;
            }
        }

        /* Update or create new meta data */
        foreach ( $this->_meta_keys as $meta_key )
            if ( property_exists( $this->_meta, $meta_key )
                    && ! empty( $this->_meta->{$meta_key} ) )
                update_post_meta( $this->wp_post->ID, $meta_key, $this->_meta->{$meta_key} );

        return $this->wp_post->ID;
    }

    /**
     * Load object properties from database.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @return  bool        True upon success, false upon failure.
     */
    public function load( $post_id=null )
    {
        $this->wp_post = $post_id ? get_post( $post_id ) : get_post( $this->wp_post->ID );
        return $this;
    }

    /**
     * Flush object cache.
     *
     * @since   0.0.1
     * @access  public
     */
    public function flush_cache()
    {
        $this->_cache = new \stdClass;
    }

}
