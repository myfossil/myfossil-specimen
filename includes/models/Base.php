<?php
/**
 * ./includes/models/Base.php
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
class Base
{
    const POST_TYPE = null;

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
    protected $_updated;
    protected $_history;

    /**
     * Create Base class.
     *
     * @param unknown $post_id (optional)
     * @param unknown $meta    (optional)
     */
    public function __construct( $post_id=null, $meta=array() )
    {
        $this->_meta = new \stdClass;
        $this->_cache = new \stdClass;
        $this->_updated = array();
        $this->_history = array();

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
     * @param string  $key
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

        if ( ( $key == 'post_id' || $key == 'id' ) && $this->wp_post
            && $this->wp_post->ID )
            return $this->wp_post->ID;

        /* Try local property */
        if ( property_exists( $this, $key ) )
            return $this->$key;

        /* Try local metadata */
        if ( property_exists( $this->_meta, $key ) )
            return $this->_meta->{$key};

        /* Try WordPress */
        if ( $this->wp_post && $this->wp_post->ID && $this->wp_post->{$key} )
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
     * @param string  $key
     * @param mixed   $value
     */
    public function __set( $key, $value )
    {
        if ( $this->{ $key } && (string) $this->{ $key } !== (string) $value )
            $this->_history[] = array(
                'key' => $key,
                'from' => $this->{ $key },
            'to' => $value
        );

        if ( $key == 'id' && $this->wp_post )
            $this->wp_post->ID = $value;

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
     * @param unknown $post_type
     * @param bool    $recursive (optional) Recurse saving of children objects as well, default false.
     * @return  bool                                True upon success, false upon failure.
     */
    protected function _save( $post_type, $recursive=false )
    {
        $current_id = $this->id;

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

        return $this->bp_activity_maybe_update( $post_type, $current_id );
    }

    /**
     *
     *
     * @return unknown
     */
    public static function bp()
    {
        return function_exists( '\bp_is_active' );
    }

    /**
     * {{{ BuddyPress integrations
     *
     * @param unknown $post_type
     * @param unknown $current_id (optional)
     * @return unknown
     */
    public function bp_activity_maybe_update( $post_type, $current_id=0 )
    {
        /*
         * Continue only if:
         *   - BuddyPress is enabled
         *   - We're updating something and it has changed OR we're making
         *   something new
         */
        $updated = ( $current_id && $this->_history );
        $created = ( ! $current_id );

        if ( self::bp() && ( $updated || $created ) ) {
            if ( $updated )
                $bp_activity_type = $post_type . '_updated';
            else
                $bp_activity_type = $post_type . '_created';

            $args = array(
                'item_id' => $this->id,
                'user_id' => \bp_loggedin_user_id(),
                'content' => json_encode( $this->_history ),
                'secondary_item_id' => $this->wp_post->post_author,
                'component' => 'myfossil',
                'type' => $bp_activity_type
            );

            bp_activity_add( $args );
        }

        return $this->id;
    }

    /**
     * BuddyPress registrations
     *
     * @param unknown $post_type
     * @return unknown
     */
    public static function register_buddypress_activities( $post_type )
    {
        // bail if buddypress doesn't exist or have activity enabled
        if ( ! self::bp() ) return false;

        foreach ( array( 'updated', 'comment', 'deleted', 'created' ) as $t ) {
            $component_id = 'myfossil';
            $type = $post_type . '_' . $t;
            $description = sprintf( '%s %s', $post_type, $t );
            $format_callback = sprintf( "%s::bp_format_activity",
                \get_called_class() );

            $label = $post_type;
            $context = array( 'activity' );
            \bp_activity_set_action( $component_id, $type, $description,
                $format_callback, $label, $context );
        }
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
        $initiator_link = \bp_core_get_userlink( $activity->user_id );
        $verbs = explode( '_', $activity->type );
        $verb = end( $verbs ) == 'comment' ? 'commented' : end( $verbs );

        $owner_link = ( $activity->user_id == $activity->secondary_item_id )
            ? 'their own' : sprintf( "%s's", \bp_core_get_userlink(
                $activity->secondary_item_id ) );

        if ( $owner_link == 'their own' && $verb == 'created' )
            $owner_link = 'a';

        $action = sprintf( '%s %s %s fossil', $initiator_link, $verb,
            $owner_link );

        if ( property_exists( $activity, 'template' ) )
            $activity->content = $activity->template;

        return apply_filters( 'bp_myfossil_activity_' . $activity->type .
            '_format', $action, $activity );
    }

    // }}}

    /**
     * Load object properties from database.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @param unknown $post_id (optional)
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
