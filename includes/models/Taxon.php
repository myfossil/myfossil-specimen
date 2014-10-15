<?php
/**
 * ./includes/models/Taxon.php
 *
 * Taxon class.
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
class Taxon extends Base implements WordPress, CRUD
{

    /**
     * Taxon.
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
            'pbdb_id' => '%d',
            'created_by_id' => '%s',
            'created_at' => '%s',
            'name' => '%s',
            'common_name' => '%s'
        );

        $this->pbdb = new PBDB\Taxon;
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
        return $wpdb->prefix . self::TABLE_PREFIX . 'taxa';
    }

    /**
     * Create table to represent Taxa using $wpdb.
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
            pbdb_id INT NULL,
            created_by_id INT NULL,
            created_at DATETIME NULL,
            name VARCHAR(45) NULL,
            common_name VARCHAR(45) NULL,
            PRIMARY KEY (id)
            ) %s";
        $sql = sprintf( $tpl, $this->table_name, charset_collate() );

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        return dbDelta( $sql );
    }

    /**
     * Factory method to create Taxon objects given the ID.
     *
     * @todo    Add WordPress hook(s)
     * @since   0.0.1
     * @access  public
     * @return  Taxon           Taxon object with properties loaded from database.
     * @param int     $id Unique identifier of object in database to create.
     */
    public static function factory( $id )
    {
        $taxon = new Taxon;
        $taxon->id = $id;
        $taxon->load();
        return $taxon;
    }

}
