<?php
/**
 * ./includes/class-myfossil-specimen-activator.php
 *
 * Fired during plugin activation
 *
 * @author Brandon Wood <bwood@atmoapps.com>
 * @package myFOSSIL
 * @subpackage  myFOSSIL/includes
 *
 * @link        https://github.com/myfossil
 * @since       0.0.1
 */

namespace myFOSSIL\Plugin\Specimen;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author     Brandon Wood <bwood@atmoapps.com>
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 *
 * @since      0.0.1
 */
class myFOSSIL_Specimen_Activator
{

    /**
     * Main function that orchestrates activation methods.
     *
     * @since   0.0.1
     * @access  public
     * @static
     */
    public static function activate()
    { 
        global $wpdb;

        $models = array(
                new Taxon,
                new Location,
                new GeologicalTimeScale,
                new GeologicalTimeInterval,
                new FossilOccurence
            );

        foreach ( $models as $model )
            $model->activate();
    }

}
