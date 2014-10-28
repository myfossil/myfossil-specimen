<?php
/**
 * ./includes/models/WordPress.php
 *
 * WordPress Interface class.
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
 * WordPress interface.
 *
 * Defines methods necessary to have an object talk to WordPress within our
 * system.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
interface WordPress {

    /**
     * Create table using $wpdb.
     *
     * @since   0.0.1
     * @access  public
     */
    public function activate();

    /**
     * Destroy (drop) table using $wpdb.
     *
     * @since   0.0.1
     * @access  public
     * @param bool    $destroy (optional) Delete database tables and data
     */
    public function deactivate();

    public static function register_cpt();

}
