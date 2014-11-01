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

use \myFOSSIL\PBDB;

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

        self::load_data();
    }

    /**
     * Load the database with data from PBDB.
     *
     * @todo    Use the PBDB client rather than the raw HTTP client
     * @see     {@link http://bit.ly/1w7janY}
     */
    public static function load_data() {
        $url = "http://paleobiodb.org/data1.1/occs/list.json" .
            "?base_name=Cetacea&interval=Miocene&show=loc,paleoloc,time&vocab=pbdb";

        $http = new \GuzzleHttp\Client;
        $fossils = $http->get( $url )->json();
        foreach ( $fossils['records'] as $record ) {
            $fossil = new FossilOccurence;
            $fossil->pbdb_id = $record['occurrence_no'];
            $fossil->created_at = date( "Y-m-d H:i:s" );
            $fossil->created_by = 1;

            $taxon = new Taxon;
            $taxon->pbdb_id = $record['taxon_no'];
            $taxon->created_by = 1;
            $taxon->created_at = $fossil->created_at;
            $taxon->name = $record['taxon_name'];
            $fossil->taxon = $taxon;

            $location = new Location;
            $location->created_by = 1;
            $location->created_at = $fossil->created_at;
            $location->latitude = $record['paleolat'];
            $location->longitude = $record['paleolng'];
            $location->country = $record['cc'];
            if ( array_key_exists( 'state', $record ) )
                $location->state = $record['state'];
            $fossil->location = $location;

            $fossil->save( true, true );
        }
    }

}
