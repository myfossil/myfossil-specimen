<?php
/**
 * ./includes/models/Location.php
 *
 * Location class.
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
 * Location.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class Location extends Base implements WordPress, CRUD
{

    /**
     * Location.
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

        $this->_keys = array(
            'id' => '%d',
            'parent_id' => '%d',
            'created_by_id' => '%d',
            'created_at' => '%s',
            'latitude_max' => '%f',
            'latitude_min' => '%f',
            'longitude_max' => '%f',
            'longitude_min' => '%f',
            'accuracy' => '%f',
            'scale' => '%s',
            'country' => '%s',
            'state' => '%s',
            'county' => '%s',
            'city' => '%s',
            'zip' => '%s',
            'address' => '%s',
            'name' => '%s',
            'url' => '%s',
            'notes' => '%s',
        );
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
        return $wpdb->prefix . self::TABLE_PREFIX . 'locations';
    }

    /**
     * Custom getter for lat/long bbox fixes.
     */
    public function __get( $key ) {
        switch ( $key ) {
            case 'latitude':
                if ( $this->latitude_max && $this->latitude_min )
                    return ( $this->latitude_max + $this->latitude_min ) / 2;
                elseif ( $this->latitude_min && !$this->latitude_max )
                    $this->latitude_max = $this->latitude_min;
                elseif ( $this->latitude_max )
                    $this->latitude_min = $this->latitude_max;
                return $this->latitude_max;
                break;
            case 'longitude':
                if ( $this->longitude_max && $this->longitude_min )
                    return ( $this->longitude_max + $this->longitude_min ) / 2;
                elseif ( $this->longitude_min && !$this->longitude_max )
                    $this->longitude_max = $this->longitude_min;
                elseif ( $this->longitude_max )
                    $this->longitude_min = $this->longitude_max;
                return $this->longitude_max;
                break;
            default:
                return parent::__get( $key );
                break;
        }
    }

    /**
     * Custom getter for lat/long bbox fixes.
     */
    public function __set( $key, $value ) {
        switch ( $key ) {
            case 'latitude':
                $this->latitude_max = $this->latitude_min = $value;
                break;
            case 'longitude':
                $this->longitude_max = $this->longitude_min = $value;
                break;
            default:
                return parent::__set( $key, $value );
                break;
        }
    }

    /**
     * Create table to represent Locations using $wpdb.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     */
    final public function activate()
    {
        $tpl = "CREATE TABLE %s (
            id INT NOT NULL AUTO_INCREMENT,
            parent_id INT NULL,
            created_by_id INT NULL,
            created_at DATETIME NULL,
            latitude_max DOUBLE NULL,
            latitude_min DOUBLE NULL,
            longitude_max DOUBLE NULL,
            longitude_min DOUBLE NULL,
            accuracy DOUBLE NULL,
            scale TEXT NULL,
            country TEXT NULL,
            state TEXT NULL,
            county TEXT NULL,
            city TEXT NULL,
            zip TEXT NULL,
            address TEXT NULL,
            name TEXT NULL,
            url TEXT NULL,
            notes TEXT NULL,
            PRIMARY KEY (id)
            ) %s";
        $sql = sprintf( $tpl, $this->table_name, charset_collate() );

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        return dbDelta( $sql );
    }

    /**
     * Factory method to create Location objects given the ID.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @return  Location           Location object with properties loaded from database.
     * @param int     $id Unique identifier of object in database to create.
     */
    public static function factory( $id )
    {
        $location = new Location;
        $location->id = $id;
        $location->load();
        return $location;
    }

}
