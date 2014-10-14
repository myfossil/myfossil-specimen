<?php
/**
 * ./myfossil-specimen.php
 *
 * @author  Brandon Wood <bwood@atmoapps.com>
 * @package myFOSSIL
 */

namespace myFOSSIL\Plugin\Specimen;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the
 * plugin Dashboard. This file also includes all of the dependencies used by
 * the plugin, registers the activation and deactivation functions, and defines
 * a function that starts the plugin.
 *
 * @link              https://github.com/myfossil/wp-plugin-specimen
 * @since             0.0.1
 * @package           myFOSSIL_Specimen
 *
 * @wordpress-plugin
 * Plugin Name:       myFOSSIL Specimen
 * Plugin URI:        https://github.com/myfossil/wp-plugin-specimen
 * Description:       Adds fossil management to WordPress + BuddyPress.
 * Version:           0.0.1
 * Author:            myFOSSIL
 * Author URI:        https://github.com/myfossil
 * License:           BSD
 * License URI:       http://opensource.org/licenses/bsd-license.html
 * Text Domain:       myfossil-specimen
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Dependencies.
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * The code that defines the custom objects to be used.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/models.php';

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-specimen-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-specimen-deactivator.php';

/**
 * This action is documented in:
 *  - includes/class-myfossil-specimen-activator.php
 */
register_activation_hook( __FILE__, array( __NAMESPACE__ . '\myFOSSIL_Specimen_Activator', 'activate' ) );

/**
 * This action is documented in:
 *  - includes/class-myfossil-specimen-deactivator.php
 */
register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\myFOSSIL_Specimen_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-specimen.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, then kicking off
 * the plugin from this point in the file does not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_myfossil_specimen()
{

    $plugin = new myFOSSIL_Specimen();
    $plugin->run();

}
run_myfossil_specimen();
