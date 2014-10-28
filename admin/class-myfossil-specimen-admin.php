<?php
/**
 * ./admin/class-myfossil-specimen-admin.php
 *
 * The dashboard-specific functionality of the plugin.
 *
 * @author      Brandon Wood <btwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/admin
 *
 * @link       https://github.com/myfossil
 * @since      0.0.1
 */

namespace myFOSSIL\Plugin\Specimen;

require_once 'partials/myfossil-specimen-admin-display.php';

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue
 * the dashboard-specific stylesheet and JavaScript.
 *
 * @package    myFOSSIL
 * @subpackage myFOSSIL/admin
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class myFOSSIL_Specimen_Admin
{

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
     * @param      string    $name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $name, $version )
    {
        $this->name = $name;
        $this->version = $version;
    }

    public function register_custom_post_types() {
        Fossil::register_cpt();
        FossilDimension::register_cpt();
        FossilLocation::register_cpt();
        Reference::register_cpt();
        Stratum::register_cpt();
        Taxon::register_cpt();
        TimeInterval::register_cpt();
    }

    public function register_taxonomies() {
        $this->_ctax_taxa();
        $this->_ctax_geochronology();
        $this->_ctax_lithostratigraphy();
    }

    // {{{ Biological Taxonomy
    private function _ctax_taxa() {
        $labels = array(
            'name'                => __( 'Taxonomic Ranks', 'myfossil-specimen' ),
            'singular_name'       => __( 'Taxonomic Rank', 'myfossil-specimen' ),
            'menu_name'           => __( 'Taxonomic Ranks', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Taxonomic Rank:', 'myfossil-specimen' ),
            'all_items'           => __( 'Taxonomic Ranks', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Taxonomic Rank', 'myfossil-specimen' ),
            'view_item'           => __( 'View Taxonomic Rank', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Taxonomic Rank', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Taxonomic Rank', 'myfossil-specimen' ),
            'new_item_name'       => __( 'New Taxonomic Rank', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Taxonomic Ranks', 'myfossil-specimen' ),
            'not_found'           => __( 'Taxonomic Rank not found', 'myfossil-specimen' ),
        );

        $args = array(
            'labels'              => $labels,
            'rewrite'             => array(
                    'slug' => 'taxon',
                    'hierarchical' => false
                ),
            'labels'              => $labels,
            'hierarchical'        => true,
            'public'              => true,
            'query_var'           => true,
        );

        register_taxonomy( 'myfs_taxa', array( 'myfs_taxon' ), $args );
        register_taxonomy_for_object_type( 'myfs_taxa', 'myfs_taxon' );
    }
    // }}}

    // {{{ Geochronology
    private function _ctax_geochronology() {
        $labels = array(
            'name'                => __( 'Geochronologies', 'myfossil-specimen' ),
            'singular_name'       => __( 'Geochronology', 'myfossil-specimen' ),
            'menu_name'           => __( 'Geochronologies', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Geochronology:', 'myfossil-specimen' ),
            'all_items'           => __( 'Geochronologies', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Geochronology', 'myfossil-specimen' ),
            'view_item'           => __( 'View Geochronology', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Geochronology', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Geochronology', 'myfossil-specimen' ),
            'new_item_name'       => __( 'New Geochronology', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Geochronologies', 'myfossil-specimen' ),
            'not_found'           => __( 'Geochronology not found', 'myfossil-specimen' ),
        );

        $args = array(
            'labels'              => $labels,
            'rewrite'             => array(
                    'slug' => 'geochronology',
                    'hierarchical' => false
                ),
            'labels'              => $labels,
            'hierarchical'        => true,
            'public'              => true,
            'query_var'           => true,
        );

        register_taxonomy( 'myfs_geochronologies', array( 'myfs_time_interval' ), $args );
        register_taxonomy_for_object_type( 'myfs_geochronologies', 'myfs_time_interval' );
    }
    // }}}

    // {{{ Lithostratigraphy
    private function _ctax_lithostratigraphy() {
        $labels = array(
            'name'                => __( 'Lithostratigraphies', 'myfossil-specimen' ),
            'singular_name'       => __( 'Lithostratigraphy', 'myfossil-specimen' ),
            'menu_name'           => __( 'Lithostratigraphies', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Lithostratigraphy:', 'myfossil-specimen' ),
            'all_items'           => __( 'Lithostratigraphies', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Lithostratigraphy', 'myfossil-specimen' ),
            'view_item'           => __( 'View Lithostratigraphy', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Lithostratigraphy', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Lithostratigraphy', 'myfossil-specimen' ),
            'new_item_name'       => __( 'New Lithostratigraphy', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Lithostratigraphies', 'myfossil-specimen' ),
            'not_found'           => __( 'Lithostratigraphy not found', 'myfossil-specimen' ),
        );

        $args = array(
            'labels'              => $labels,
            'rewrite'             => array(
                    'slug' => 'lithostratigraphy',
                    'hierarchical' => false
                ),
            'labels'              => $labels,
            'hierarchical'        => true,
            'public'              => true,
            'query_var'           => true,
        );

        register_taxonomy( 'myfs_lithostratigraphies', array( 'myfs_stratum' ), $args );
        register_taxonomy_for_object_type( 'myfs_lithostratigraphies', 'myfs_stratum' );
    }
    // }}}

    private function _load_taxonomy_terms() {
        $this->_load_ctax_taxa();
        $this->_load_ctax_geochronologies();
        $this->_load_ctax_lithostratigraphies();
    }

    // {{{ Load Default Taxonomic Data
    private function _load_taxonomy( $taxonomy, $terms ) {
        $parent_id = 0;
        foreach ( $terms as $term ) {
            $args = array(
                    'parent' => $parent_id,
                    'slug' => $term,
                );
            $_ = wp_insert_term( ucfirst( $term ), $taxonomy, $args );
            $parent_id = $_['term_id'];
        }
    }

    // {{{ Load Taxa
    private function _load_ctax_taxa() {
        $taxonomy = 'myfs_taxa';
        $terms = array( 'domain', 'kingdom', 'phylum', 'class', 'order',
                'family', 'genus', 'species' );
        $this->_load_taxonomy( $taxonomy, $terms );
    }
    // }}}

    // {{{ Load Geochronologies
    private function _load_ctax_geochronologies() {
        $taxonomy = 'myfs_geochronologies';
        $terms = array( 'eon', 'era', 'period', 'epoch', 'age', 'chron' );
        $this->_load_taxonomy( $taxonomy, $terms );
    }
    // }}}

    // {{{ Load Lithostratigraphies
    private function _load_ctax_lithostratigraphies() {
        $taxonomy = 'myfs_lithostratigraphies';
        $terms = array( 'supergroup', 'group', 'formation', 'member', 'bed' );
        $this->_load_taxonomy( $taxonomy, $terms );
    }
    // }}}

    // }}}

    // {{{ Load Default Geochronology
    private function _load_time_intervals() {
        /* 
         * Map data from PBDB to meaningful intervals.
         *
         * @link http://paleobiodb.org/data1.1/scales/single.txt?id=1
         */
        $levels = array(
                1 => 'eon',
                2 => 'era', 
                3 => 'period',
                4 => 'epoch',
                5 => 'age'
            );
        $geochronology = array();
        foreach ( $levels as $idx => $geoc )
            $geochronology[$idx] = term_exists( ucfirst( $geoc ),
                    'myfs_geochronologies' );

        /**
         * @todo create references posts.
         */

        /* Map columns from CSV */
        $fields = array( 
                'level' => 'int', 
                'interval_name' => 'str', 
                'color' => 'str', 
                'late_age' => 'float',
                'early_age' => 'float', 
                'reference_no' => 'int' 
            );

        // Set default filename
            $filename = plugin_dir_path( dirname( __FILE__ ) ) .
                'admin/data/intervals.csv';

        // Exit if the file doesn't exist.
        if ( !file_exists( $filename ) )
            return -2;

        // Load the entire CSV 
        $csv = array_map( 'str_getcsv', file( $filename ) );

        // Load data as posts
        foreach ( $csv as $raw_data ) {
            // skip header
            if ( $raw_data[0] == 'level' ) continue;

            // Parse data from CSV reader
            $data = array();
            $idx = 0;
            foreach ( $fields as $field_name => $field_type ) {
                switch ( $field_type ) {
                    case 'int':
                        $data[$field_name] = (int) $raw_data[$idx];
                        break;
                    case 'float':
                        $data[$field_name] = (float) $raw_data[$idx];
                        break;
                    case 'str':
                        $data[$field_name] = (string) $raw_data[$idx];
                        break;
                }
                $idx++;
            }

            $post_args = array(
                    'post_title'    => $data['interval_name'],
                    'post_status'   => 'publish',
                    'post_type'     => 'myfs_time_interval',
                );

            // Insert the post into the database
            $post_id = wp_insert_post( $post_args );

            // Check that it actually went in
            if ( !$post_id )
                return -1;

            // Set Geochronology
            wp_set_post_terms( $post_id, $geochronology[$data['level']],
                    'myfs_geochronologies' );
    
            // Load in ACF data for the Place
            $acf_fields = array( 'color', 'late_age', 'early_age' );
            foreach ( $acf_fields as $field_name )
                update_field( $field_name, $data[$field_name], $post_id );
        }

        return 1;
    }
    // }}}

    public function admin_menu() {
        $this->_cleanup_metaboxes();
        $this->_add_menu_separator();
        $this->_add_tools_page();
    }

    // {{{ Adminitrative Panel Fixes
    private function _cleanup_metaboxes() {
        $prefix = 'myfs_';
        $cpts = array( 'taxon', 'stratum', 'time_interval', 'fossil',
            'fossil_col', 'reference' );
        foreach ( $cpts as $cpt )
            remove_meta_box( 'postcustom', $prefix . $cpt, 'normal' );
    }

    private function _add_menu_separator() {
        global $menu;

        $position = 0;
        $sep_idx = 0;
        foreach($menu as $offset => $section) {
            if ( substr( $section[2], 0, 9) == 'separator' )
                $sep_idx++;
        }

        foreach($menu as $offset => $section) {
            $section_name = $section[0];
            if ( $section_name == 'Taxa' ) {
                // Backup until we don't hit a menu item
                $idx = $offset - 1;
                while ( array_key_exists( $idx, $menu ) && $idx > 0 )
                    $idx--;

                // Exit if we have no room for a separator somehow...
                if ( $idx == 0 ) break;

                // Create the separator
                $menu[$idx + 1] = array( 
                        '', 
                        'read',
                        "separator{$sep_idx}", 
                        '', 
                        'wp-menu-separator'
                    );

                break;
            }
        }

        ksort( $menu );
    }

    private function _add_tools_page() {
        add_management_page( 'myFOSSIL Specimen', 'myFOSSIL Specimen',
                'manage_options', 'myfossil-specimen',
                'myFOSSIL\Plugin\Specimen\admin_tools_page' );
    }
    // }}}

    /**
     * AJAX call handler
     */
    public function ajax_handler() {
        header('Content-Type: application/json');

        // Check nonce
        if ( !check_ajax_referer( 'myfs_nonce', 'nonce', false ) ) {
            $return_args = array(
                "result" => "Error",
                "message" => "403 Forbidden",
                );

            echo json_encode( $return_args );
            die;
        }

        switch ( $_POST['action'] ) {
            case 'myfs_load_terms':
                $this->_load_taxonomy_terms();                
                echo "1";
                die;
                break;
            case 'myfs_load_geochronology':
                if ( $this->_load_time_intervals() > 0 )
                    echo "1";
                else
                    echo "0";
                die;
                break;
            case 'myfs_load_default_fossils':
                if ( Fossil::load_defaults() )
                    echo "1";
                else
                    echo "0";
                die;
                break;
        }
    }

    // {{{ Enqueues
    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    0.0.1
     */
    public function enqueue_styles()
    {
        wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) .
            'css/myfossil-specimen-admin.css', array(), $this->version,
            'all' );
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    0.0.1
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) .
            'js/myfossil-specimen-admin.js', array( 'jquery' ),
            $this->version, false );
    }
    // }}}
}
