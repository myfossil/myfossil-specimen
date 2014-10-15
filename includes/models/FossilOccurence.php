<?php
/**
 * ./includes/models/FossilOccurence.php
 *
 * FossilOccurence class.
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
 * FossilOccurence.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class FossilOccurence extends Base implements WordPress, CRUD
{

    /**
     * FossilOccurence.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    public function __construct()
    {
        parent::__construct();

        global $wpdb;
        $this->table_name = self::get_table_name();

        /*
         * Set properties.
         */
        $this->_keys = array(
            'id' => '%d',
            'parent_id' => '%d',
            'pbdb_id' => '%d',
            'taxon_id' => '%d',
            'time_interval_id' => '%d',
            'geology_id' => '%d',
            'location_id' => '%d',
            'created_by' => '%s',
            'created_at' => '%s',
            'name' => '%s',
        );

        $this->pbdb = new PBDB\FossilOccurence;
    }

    /**
     * Returns name of the table for this object.
     *
     * @since   0.0.1
     * @static
     * @access  public
     * @return  string  Name of the table as stored in the WordPress database.
     */
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::TABLE_PREFIX . 'fossil_occurences';
    }

    /**
     * Custom getters specific to FossilOccurence's
     *
     * @todo    Break up swith statements into private methods
     * @since   0.0.1
     * @access  public
     * @param unknown $key
     * @return unknown
     */
    public function __get( $key )
    {
        switch ( $key ) {
        case 'taxon':
            return $this->get_taxon();
            break;
        case 'time_interval':
            return $this->get_time_interval();
            break;
        case 'geology':
            return $this->get_geology();
            break;
        case 'location':
            return $this->get_location();
            break;
        case 'parent':
            return $this->get_parent();
            break;
        default:
            return parent::__get( $key );
            break;
        }
    }

    /**
     *
     *
     * @param unknown $id_key
     * @param unknown $cache_key
     * @param unknown $cls
     * @return unknown
     */
    private function _get_object( $id_key, $cache_key, $cls )
    {
        if ( !$this->{ $id_key } ) return;

        if ( !property_exists( $this->_cache, $cache_key ) )
            $this->_cache->$cache_key = $this->$id_key ? $cls::factory(
                $this->$id_key ) : null;

        return $this->_cache->$cache_key;
    }

    /**
     *
     *
     * @return unknown
     */
    public function get_taxon()
    {
        return $this->_get_object( 'taxon_id', 'taxon', new Taxon );
    }

    /**
     *
     *
     * @return unknown
     */
    public function get_time_interval()
    {
        return $this->_get_object( 'time_interval_id', 'time_interval',
            new GeologicalTimeInterval );
    }

    /**
     *
     *
     * @return unknown
     */
    public function get_geology()
    {
        return $this->_get_object( 'geology_id', 'geology', new Geology );
    }

    /**
     *
     *
     * @return unknown
     */
    public function get_location()
    {
        return $this->_get_object( 'location_id', 'location', new Location );
    }

    /**
     *
     *
     * @return unknown
     */
    public function get_parent()
    {
        return $this->_get_object( 'parent_id', 'parent', self);
    }


    /**
     * Custom setters specific to FossilOccurence's
     *
     * @todo    Break up swith statements into private methods
     * @since   0.0.1
     * @access  public
     * @param unknown $key
     * @param unknown $value
     * @return unknown
     */
    public function __set( $key, $value )
    {
        switch ( $key ) {
            case 'taxon':
                return $this->set_taxon( $value );
                break;
            case 'time_interval':
                return $this->set_time_interval( $value );
                break;
            case 'geology':
                return $this->set_geology( $value );
                break;
            case 'location':
                return $this->set_location( $value );
                break;
            case 'parent':
                return $this->set_parent( $value );
                break;
            default:
                return parent::__set( $key, $value );
                break;
        }
    }

    /**
     *
     *
     * @param unknown $id_key
     * @param unknown $cache_key
     * @param unknown $new_object
     * @param unknown $cls        (optional)
     */
    private function _set_object( $id_key, $cache_key, $new_object, $cls=null )
    {
        if ( $new_object->id )
            $this->$id_key = $new_object->id;
        $this->_cache->$cache_key = $new_object;
    }

    /**
     *
     *
     * @param unknown $obj
     * @return unknown
     */
    public function set_taxon( $obj )
    {
        return $this->_set_object( 'taxon_id', 'taxon', $obj, new Taxon );
    }

    /**
     *
     *
     * @param unknown $obj
     * @return unknown
     */
    public function set_time_interval( $obj )
    {
        return $this->_set_object( 'time_interval_id', 'time_interval',
            $obj, new GeologicalTimeInterval );
    }

    /**
     *
     *
     * @param unknown $obj
     * @return unknown
     */
    public function set_geology( $obj )
    {
        return $this->_set_object( 'geology_id', 'geology', $obj, new Geology );
    }

    /**
     *
     *
     * @param unknown $obj
     * @return unknown
     */
    public function set_location( $obj )
    {
        return $this->_set_object( 'location_id', 'location', $obj, new Location );
    }

    /**
     *
     *
     * @param unknown $obj
     * @return unknown
     */
    public function set_parent( $obj )
    {
        return $this->_set_object( 'parent_id', 'parent', $obj, self );
    }

    /**
     *
     *
     * @param unknown $force_overwrite (optional)
     * @param unknown $recurse         (optional)
     * @return unknown
     */
    public function save( $force_overwrite=false, $recurse=false )
    {

        // Also save objects in cache back to database
        if ( $recurse ) {
            $id_map = array( 'taxon' => 'taxon_id', 'time_interval' =>
                    'time_interval_id', 'location' => 'location_id', 'parent'
                    => 'parent_id' );
            foreach ( $this->_cache as $k => $v ) {
                $obj_id = $this->_cache->{$k}->save( $force_overwrite );
                $this->{ $id_map[ $k ] } = $obj_id;
                $save_statuses[] = $obj_id;
            }
        }

        $save_statuses = array( parent::save( $force_overwrite ) );

        return !in_array( false, $save_statuses );
    }

    /**
     * Create table to represent FossilOccurences.
     *
     * Called during WP plugin activation.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    final public function activate()
    {
        return $this->_create_table();
    }

    /**
     * Create table representing FossilOccurences.
     *
     * @since   0.0.1
     * @access  private
     * @return unknown
     */
    private function _create_table()
    {
        $tpl = "CREATE TABLE %s (
            id INT NOT NULL AUTO_INCREMENT,
            pbdb_id INT NULL,
            parent_id INT NULL,
            taxon_id INT NULL,
            time_interval_id INT NULL,
            location_id INT NULL,
            created_by INT NULL,
            created_at DATETIME NULL,
            name VARCHAR(45) NULL,
            PRIMARY KEY (id)
            ) %s";
        $sql = sprintf( $tpl, $this->table_name, charset_collate() );

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        return dbDelta( $sql );
    }

    /**
     * Destroy (drop) table to represent FossilOccurence using $wpdb.
     *
     * Called during WP plugin deactivation.
     *
     * @todo    Abstract this out to a parent class or utility class (if possible).
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @param bool    $destroy Destroy table and data.
     */
    final public function deactivate( $destroy=false )
    {
        // Exit if we're not destroying data
        if ( !$destroy ) return;
        return $this->_drop_table();
    }

    /**
     * Drop table for FossilOccurences, destroying data.
     *
     * @since   0.0.1
     * @access  private
     * @return unknown
     */
    private function _drop_table()
    {
        $tpl = "DROP TABLE %s";
        $sql = sprintf( $tpl, $this->table_name );

        global $wpdb;
        return $wpdb->query( $sql );
    }

    // {{{ Postponed feature: Suggestions
    /*
    private function _drop_table_properties() {
        $tpl = "DROP TABLE %s_suggestions";
        $sql = sprintf( $tpl, $this->table_name );

        global $wpdb;
        return $wpdb->query( $sql );
    }

    private function _create_table_suggestions() {
        $tpl = "CREATE TABLE %s_suggestions (
            fid INT NOT NULL,
            pbdb_id INT NULL,
            created_by INT NULL,
            created_at DATETIME NULL,
            accepted_by INT NULL,
            accepted_at DATETIME NULL,
            name VARCHAR(45) NOT NULL,
            val TEXT NULL,
            val_type VARCHAR(45) NULL DEFAULT 'string',
            comment TEXT NULL
            ) %s";
        $sql = sprintf( $tpl, $this->table_name, charset_collate() );

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        return dbDelta( $sql );
    }
    */
    // }}}

}
