<?php
/**
 * ./class-myfossil-specimen-i18n.php
 *
 * @author Brandon Wood <bwood@atmoapps.com>
 * @package myFOSSIL
 */


namespace myFOSSIL\Plugin\Specimen;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin so that its
 * ready for translation.
 *
 * @link       http://atmoapps.com
 * @since      0.0.1
 *
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin so that its
 * ready for translation.
 *
 * @since      0.0.1
 * @package    myFOSSIL
 * @subpackage myFOSSIL/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class myFOSSIL_Specimen_i18n
{

    /**
     * The domain specified for this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain;

    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.0.1
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            $this->domain,
            false,
            dirname( dirname( plugin_basename( realpath( __FILE__ ) ) ) ) . '/languages/'
        );

    }

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since    0.0.1
     * @param string  $domain The domain that represents the locale of this plugin.
     */
    public function set_domain( $domain )
    {
        $this->domain = $domain;
    }

}
