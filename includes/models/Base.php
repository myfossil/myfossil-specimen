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
    const TABLE_PREFIX = 'myfs_';

    /**
     * Properties that are to be stored in the database.
     *
     * @since   0.0.1
     * @access  protected
     * @var     array
     */
    protected $_properties = array();

    /**
     * Keys that are to be stored in the database.
     *
     * Types of the keys to be stored in the database.
     *
     * Should be in the format of %s for string, %d for number.
     *
     * @since   0.0.1
     * @access  protected
     * @var     array
     */
    protected $_keys = array();

    /**
     * Cache for objects.
     *
     * @since   0.0.1
     * @access  protected
     */
    protected $_cache;

    /**
     * WordPress database table name.
     *
     * @since   0.0.1
     * @access  public
     * @var     string  $table_name;
     */
    public $table_name;

    /**
     * PBDB query object for this class.
     */
    public $pbdb;

    /**
     * Create Base class.
     */
    public function __construct()
    {
        $this->_cache = new \stdClass;
    }

    /**
     * Custom getter for properties that span multiple data sources.
     *
     * @since   0.0.1
     * @access  public
     * @param string $key
     * @return mixed    value of the property, null if not found.
     */
    public function __get( $key )
    {
        if ( property_exists( $this, $key ) )
            return $this->$key;

        if ( array_key_exists( $key, $this->_properties ) )
            return $this->_properties[ $key ];

        if ( !$this->pbdb || $key == 'id' ) return;

        try {
            return $this->pbdb->$key;
        } catch ( \DomainException $e ) {
            // Fail silently.
            return;
        }

        return;
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
        if ( $key == 'pbdb_id' && $this->pbdb )
            $this->pbdb->oid = $value;

        if ( property_exists( $this, $key ) ) {
            $this->$key = $value;
        } else {
            /*
            if ( !array_key_exists( $key, $this->_keys ) )
                trigger_error( sprintf( "Setting unknown property %s in %s.",
                        $key, __CLASS__ ), E_USER_WARNING );
            */
            $this->_properties[ $key ] = $value;
        }
    }

    /**
     * Destroy (drop) table to represent Taxa using $wpdb.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @param bool    $destroy (optional) Destroy table and data.
     * @return mixed
     */
    public function deactivate( $destroy=false )
    {
        // Exit if we're not destroying data
        if ( !$destroy ) return;

        $tpl = "DROP TABLE %s";
        $sql = sprintf( $tpl, $this->table_name );

        global $wpdb;
        return $wpdb->query( $sql );
    }

    /**
     * Update or create new object in the database.
     *
     * If this object does not currently exist in the database, identified by
     * $this->id, then a new object will be created in the database and the new
     * id set to $this->id, otherwise overwrite all properties of the object
     * and update the last modified column.
     *
     * @todo    Implement history of objects, using parent_id.
     * @todo    Add WordPress hook(s)
     *
     * @since   0.0.1
     * @access  public
     * @see     {@link http://codex.wordpress.org/Class_Reference/wpdb#UPDATE_rows}
     *
     * @param   bool    $force_overwrite (optional) Overwrite values in the database, default false.
     * @return  bool                                True upon success, false upon failure.
     */
    public function save( $force_overwrite=false )
    {
        if ( !$this->id ) return $this->create();

        $tpl = "SELECT * FROM %s WHERE id = %d";
        $sql = sprintf( $tpl, $this->table_name, $this->id );

        global $wpdb;
        if ( !is_null( $wpdb->get_var( $sql ) ) ) {
            // Object exists in database, update
            return $this->update( $force_overwrite );
        } else {
            // Object does not yet exist in database, create
            return $this->create();
        }
    }

    /**
     * Create arrays for $wpdb->update and $wpdb->insert.
     *
     * @param bool  $include_empty (optional)
     * @return mixed
     */
    public function describe_properties( $include_empty=false )
    {
        $properties = array();
        $types = array();
        foreach ( $this->_keys as $k => $t ) {
            if ( array_key_exists( $k, $this->_properties ) ) {
                $v = $this->_properties[ $k ];
                if ( !empty( $v ) || $include_empty ) {
                    $properties[ $k ] = $v;
                    $types[] = $t;
                }
            }
        }

        if ( count( $properties ) > 0 )
            return array( $properties, $types );
        else
            return false;
    }

    /**
     * Update the object in the database.
     *
     * @param bool  $force_overwrite (optional)
     * @return mixed
     */
    public function update( $force_overwrite=false )
    {
        if ( $r = $this->describe_properties( $force_overwrite ) ) {
            $properties = $r[0];
            $types = $r[1];

            global $wpdb;
            if ( !( $wpdb->update( $this->table_name, $properties,
                        array( 'id' => $this->id ), $types, array( '%d' ) )
                    === false ) ) {
                return $this->id;
            }
        }
        return false;
    }

    /**
     * Create new object in the database, assumes that it does not exist.
     *
     * @return mixed
     */
    public function create()
    {
        if ( $r = $this->describe_properties() ) {
            $properties = $r[0];
            $types = $r[1];

            global $wpdb;
            $wpdb->insert( $this->table_name, $properties, $types );

            $this->id = $wpdb->insert_id;
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * Load object properties from database.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @param   bool    $overwrite (optional)   Overwrite local properties with values from database.
     * @return  bool                            True upon success, false upon failure.
     */
    public function load( $overwrite=false )
    {
        if ( !$this->id && !$this->pbdb && !$this->pbdb->id ) {
            trigger_error( sprintf( "Cannot load %s without an id.", __CLASS__ ) );
            return false;
        }

        // Prepare query
        $tpl = "SELECT * FROM %s WHERE id = %d";
        $sql = sprintf( $tpl, $this->table_name, $this->id );

        // Query database and load properties
        global $wpdb;
        if ( !is_null( $r = $wpdb->get_row( $sql, ARRAY_A ) ) ) {
            foreach ( $r as $k => $v ) {
                if ( !empty( $v ) && empty( $this->$k ) || $overwrite ) {
                    $this->$k = $v;
                }
            }
        }
        
        // Get PBDB data
        if ( $this->pbdb && method_exists( $this->pbdb, 'load' ) )
            $this->pbdb->load();

        return $this;
    }

    /**
     * Delete Taxon object from database.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @return  bool    True upon success, false upon failure.
     */
    public function delete()
    {
        if ( !$this->id ) return;

        global $wpdb;
        return $wpdb->delete( $this->table_name, array( 'id' => $this->id ), array( '%d' ) );
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
