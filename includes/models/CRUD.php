<?php
/**
 * ./includes/models/CRUD.php
 *
 * CRUD Interface class.
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
 * CRUD interface.
 *
 * Defines methods necessary to have an object have CRUD functionality.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
interface CRUD 
{

    /**
     * Create or update object in database.
     *
     * @since   0.0.1
     * @access  public
     * @param   bool    $force_overwrite (optional) Force overwrite of values in the database, default false.
     */
    public function save();

    /**
     * Get object from database.
     *
     * @since   0.0.1
     * @access  public
     * @param   bool    $overwrite (optional) Overwrite local properties with values from database.
     */
    public function load();

    /**
     * Delete object from database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function delete();

}
