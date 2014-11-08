<?php
namespace myFOSSIL\Plugin\Specimen;

require_once( 'partials/myfossil-specimen-public-display.php' );

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://atmoapps.com
 * @since      0.0.1
 *
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    myFOSSIL
 * @subpackage myFOSSIL/admin
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class myFOSSIL_Specimen_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

    public function bp_register_activity_actions() {
        return Fossil::register_buddypress_activities();
    }

    /**
     * AJAX call handler
     */
    public function ajax_handler() {
        header('Content-Type: application/json');

        // Check nonce
        if ( !check_ajax_referer( 'myfossil_specimen', 'nonce', false ) ) {
            $return_args = array(
                "result" => "Error",
                "message" => "403 Forbidden",
                );

            echo json_encode( $return_args );
            die;
        }

        $fossil = new Fossil( $_POST['post_id'] );

        switch ( $_POST['action'] ) {
            case 'myfossil_save_taxon':
                $taxon = new Taxon;
                $taxon->pbdb_id = $_POST['taxon']['pbdb'];
                $taxon->name    = $_POST['taxon']['name'];
                $taxon->rank    = $_POST['taxon']['rank'];

                $fossil->taxon_id = $taxon->save();

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_geochronology':
                $ti = new TimeInterval;
                $ti->pbdb_id = $_POST['geochronology']['pbdb'];
                $ti->color   = $_POST['geochronology']['color'];
                $ti->level   = $_POST['geochronology']['level'];
                $ti->name    = $_POST['geochronology']['name'];
                $fossil->time_interval_id = $ti->save();
                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_lithostratigraphy':
                foreach ( Stratum::get_ranks() as $rank ) {
                    if ( ! array_key_exists( $rank, $_POST['strata'] ) )
                        continue;

                    $stratum = new Stratum;
                    $stratum->name = $_POST['strata'][$rank];
                    $stratum_id_key = sprintf( 'stratum_%s_id', $rank );
                    $fossil->{ $stratum_id_key } = $stratum->save();
                }

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_dimensions':
                // Dimensions coming in as *centimeters*
                $length = (float) $_POST['length'];
                $width  = (float) $_POST['width'];
                $height = (float) $_POST['height'];
                
                $dim = new FossilDimension;
                $dim->length = $length / 100; // convert to meters
                $dim->width  = $width  / 100; // convert to meters
                $dim->height = $height / 100; // convert to meters

                $fossil->dimension_id = $dim->save();

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_location':
                $location = new FossilLocation;
                foreach ( array( 'latitude', 'longitude', 'country', 'state',
                            'county', 'city' ) as $k ) 
                    $location->{ $k } = $_POST['location'][$k];

                $fossil->location_id = $location->save();

                echo json_encode( $fossil->save() );
                die;
                break;
        }

    }

}
