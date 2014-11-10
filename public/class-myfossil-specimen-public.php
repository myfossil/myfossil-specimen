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

    public function bp_add_member_fossil_nav_items() {
        global $bp;

        \bp_core_new_nav_item(
            array(
                'name' => 'Fossils',
                'slug' => 'fossils',
                'default_subnav_slug' => 'fossils',
                'parent_url' => bp_displayed_user_domain(),
                'parent_slug' => $bp->members->slug . bp_displayed_user_id(),
                'position' => 50,
                'show_for_displayed_user' => true,
                'screen_function' => 'fossil_view_member_fossils'
            )
        );
    }

    public static function upload_user_file( $file=array(), $post_id=0 )
    {
        require_once( ABSPATH . 'wp-admin/includes/admin.php' );

        $file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

        if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
            return array( $file_return, $file );
        } else {
            $filename = $file_return['file'];

            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $file_return['url']
            );

            $attachment_id = wp_insert_attachment( $attachment, $file_return['url'], $post_id );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );

            if ( 0 < intval( $attachment_id ) ) {
                return $attachment_id;
            }
        }

        return -2;
    }

    /**
     * AJAX call handler
     */
    public function ajax_handler() {
        header('Content-Type: application/json');

        // Check nonce
        if ( !check_ajax_referer( 'myfossil_specimen', 'nonce', false ) ) {
            header( 'HTTP/1.0 403 Forbidden' );
            $return_args = array(
                "result" => "Error",
                "message" => "403 Forbidden",
                );

            echo json_encode( $return_args );
            die;
        }


        switch ( $_POST['action'] ) {
            // {{{ save
            case 'myfossil_save_taxon':
                $fossil = new Fossil( $_POST['post_id'] );

                if ( $fossil->taxon_id )
                    $taxon = new Taxon( $fossil->taxon_id );
                else
                    $taxon = new Taxon;

                $taxon->pbdb_id = $_POST['taxon']['pbdb'];
                $taxon->name    = $_POST['taxon']['name'];
                $taxon->rank    = $_POST['taxon']['rank'];

                $fossil->taxon_id = $taxon->save(); 

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_geochronology':
                $fossil = new Fossil( $_POST['post_id'] );

                if ( $fossil->time_interval_id )
                    $ti = new TimeInterval( $fossil->time_interval_id );
                else
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
                $fossil = new Fossil( $_POST['post_id'] );

                foreach ( Stratum::get_ranks() as $rank ) {
                    if ( ! array_key_exists( $rank, $_POST['strata'] ) )
                        continue;

                    $stratum_id_key = sprintf( 'stratum_%s_id', $rank );

                    if ( $fossil->{ $stratum_id_key } )
                        $stratum = new Stratum( $fossil->{ $stratum_id_key } );
                    else
                        $stratum = new Stratum;

                    $stratum->name = $_POST['strata'][$rank];

                    $fossil->{ $stratum_id_key } = $stratum->save();
                }

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_dimensions':
                $fossil = new Fossil( $_POST['post_id'] );
                
                if ( $fossil->dimension_id )
                    $dim = new FossilDimension( $fossil->dimension_id );
                else
                    $dim = new FossilDimension;

                // Dimensions coming in as *centimeters*
                $length = (float) $_POST['length'];
                $width  = (float) $_POST['width'];
                $height = (float) $_POST['height'];

                $dim->length = $length / 100; // convert to meters
                $dim->width  = $width  / 100; // convert to meters
                $dim->height = $height / 100; // convert to meters

                $fossil->dimension_id = $dim->save();

                echo json_encode( $fossil->save() );
                die;
                break;

            case 'myfossil_save_location':
                $fossil = new Fossil( $_POST['post_id'] );

                if ( $fossil->location_id )
                    $location = new FossilLocation( $fossil->location_id );
                else
                    $location = new FossilLocation;

                foreach ( array( 'latitude', 'longitude', 'country', 'state',
                            'county', 'city' ) as $k ) {
                    $location->{ $k } = $_POST['location'][$k];
                }

                $fossil->location_id = $location->save();

                echo json_encode( $fossil->save() );
                die;
                break;
                
            case 'myfossil_save_status':
                if ( $_POST['post_id'] <= 0 ) die;

                $args = array(
                        'ID'     => $_POST['post_id'],
                        'post_status' => $_POST['post_status']
                    );

                echo json_encode( wp_update_post( $args ) );
                die;
                break;
            // }}}

            // {{{ create
            case 'myfossil_create_fossil':
                $fossil = new Fossil;
                echo json_encode( $fossil->save() );
                die;
                break;
            // }}}

            case 'myfossil_upload_fossil_image':
                if ( empty( $_FILES ) ) {
                    header( 'HTTP/1.0 400 Bad Request' );
                    echo json_encode(
                            array(
                                'result' => 'Error',
                                'message' => '400 Bad Request'
                            )
                        );
                    die;
                }

                $fh = $_FILES['files'];
                foreach ( $_FILES['files'] as $k => $v ) {
                    $fh[$k] = $v[0];
                }

                echo json_encode( self::upload_user_file( $fh, $_POST['post_id'] ) );
                die;
                break;
        }

    }

    public function add_rewrite_tags() {
        add_rewrite_tag( '%fossil_id%', '([^&/]+)' );
        add_rewrite_tag( '%fossil_view%', '(main|history|discussion)' );
    }

    public function fix_fossil_rewrites() {
        add_rewrite_rule(
                '^fossils/([^/]*)/(main|history|discussion)/?',
                'index.php?pagename=fossils' . '&fossil_id=$matches[1]' .
                    '&fossil_view=$matches[2]',
                'top'
        );

        add_rewrite_rule(
                '^fossils/([^/]*)/?',
                'index.php?pagename=fossils' . '&fossil_id=$matches[1]' .
                    '&fossil_view=main',
                'top'
        );

    }

}
