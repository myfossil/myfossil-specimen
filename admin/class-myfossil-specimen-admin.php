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
        $this->_cpt_init_taxon();
        $this->_cpt_init_stratum();
        $this->_cpt_init_time_interval();
        $this->_cpt_init_fossil();
        $this->_cpt_init_fossil_collection();
        $this->_cpt_init_reference();
    }

    // {{{ Custom WordPress Post Types
    private function _cpt_init_taxon() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Taxa', 'myfossil-specimen' ),
            'singular_name'       => __( 'Taxon', 'myfossil-specimen' ), 
            'menu_name'           => __( 'Taxa', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Taxon:', 'myfossil-specimen' ),
            'all_items'           => __( 'Taxa', 'myfossil-specimen' ),
            'view_item'           => __( 'View Taxon', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Taxon', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Taxon', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Taxon', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Taxon', 'myfossil-specimen' ),
            'not_found'           => __( 'Taxon not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Taxon not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_taxon', 'myfossil-specimen' ),
            'description'         => __( 'Biological classification representations (taxa)', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'author', 'thumbnail',
                'custom-fields', 'comments' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-editor-ul'
        );
        // }}}
        register_post_type( 'myfs_taxon', $args );
    }

    private function _cpt_init_stratum() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Strata', 'myfossil-specimen' ),
            'singular_name'       => __( 'Stratum', 'myfossil-specimen' ),
            'menu_name'           => __( 'Strata', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Stratum:', 'myfossil-specimen' ),
            'all_items'           => __( 'Strata', 'myfossil-specimen' ),
            'view_item'           => __( 'View Stratum', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Stratum', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Stratum', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Stratum', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Stratum', 'myfossil-specimen' ),
            'not_found'           => __( 'Stratum not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Stratum not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_stratum', 'myfossil-specimen' ),
            'description'         => __( 'Represents a geological stratum', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'author', 'thumbnail',
                'custom-fields', 'comments' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-tagcloud'
        );
        // }}}
        register_post_type( 'myfs_stratum', $args );
    }

    private function _cpt_init_time_interval() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Time Intervals', 'myfossil-specimen' ),
            'singular_name'       => __( 'Time Interval', 'myfossil-specimen' ),
            'menu_name'           => __( 'Time Intervals', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Time Interval:', 'myfossil-specimen' ),
            'all_items'           => __( 'Time Intervals', 'myfossil-specimen' ),
            'view_item'           => __( 'View Time Interval', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Time Interval', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Time Interval', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Time Interval', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Time Interval', 'myfossil-specimen' ),
            'not_found'           => __( 'Time Interval not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Time Interval not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_time_interval', 'myfossil-specimen' ),
            'description'         => __( 'Represents a time interval', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'author', 'thumbnail',
                'custom-fields', 'comments' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-backup'
        );
        // }}}
        register_post_type( 'myfs_time_interval', $args );
    }

    private function _cpt_init_fossil() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Fossils', 'myfossil-specimen' ),
            'singular_name'       => __( 'Fossil', 'myfossil-specimen' ),
            'menu_name'           => __( 'Fossils', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Fossil:', 'myfossil-specimen' ),
            'all_items'           => __( 'Fossils', 'myfossil-specimen' ),
            'view_item'           => __( 'View Fossil', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Fossil', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Fossil', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Fossil', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Fossil', 'myfossil-specimen' ),
            'not_found'           => __( 'Fossil not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Fossil not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_fossil', 'myfossil-specimen' ),
            'description'         => __( 'Represents a fossil', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author',
                'thumbnail', 'custom-fields', 'comments', 'revisions',
                'post-formats' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-carrot'
        );
        // }}}
        register_post_type( 'myfs_fossil', $args );
    }

    private function _cpt_init_fossil_collection() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Fossil Collections', 'myfossil-specimen' ),
            'singular_name'       => __( 'Fossil Collection', 'myfossil-specimen' ),
            'menu_name'           => __( 'Fossil Collections', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Fossil Collection:', 'myfossil-specimen' ),
            'all_items'           => __( 'Fossil Collections', 'myfossil-specimen' ),
            'view_item'           => __( 'View Fossil Collection', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Fossil Collection', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Fossil Collection', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Fossil Collection', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Fossil Collection', 'myfossil-specimen' ),
            'not_found'           => __( 'Fossil Collection not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Fossil Collection not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_fossil_collection', 'myfossil-specimen' ),
            'description'         => __( 'Represents a fossil collection', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author',
                'thumbnail', 'custom-fields', 'comments', 'revisions',
                'post-formats' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-admin-site'
        );
        // }}}
        register_post_type( 'myfs_fossil_col', $args );
    }

    private function _cpt_init_reference() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'References', 'myfossil-specimen' ),
            'singular_name'       => __( 'Reference', 'myfossil-specimen' ), 
            'menu_name'           => __( 'References', 'myfossil-specimen' ),
            'parent_item_colon'   => __( 'Parent Reference:', 'myfossil-specimen' ),
            'all_items'           => __( 'References', 'myfossil-specimen' ),
            'view_item'           => __( 'View Reference', 'myfossil-specimen' ),
            'add_new_item'        => __( 'Add New Reference', 'myfossil-specimen' ),
            'add_new'             => __( 'Add New', 'myfossil-specimen' ),
            'edit_item'           => __( 'Edit Reference', 'myfossil-specimen' ),
            'update_item'         => __( 'Update Reference', 'myfossil-specimen' ),
            'search_items'        => __( 'Search Reference', 'myfossil-specimen' ),
            'not_found'           => __( 'Reference not found', 'myfossil-specimen' ),
            'not_found_in_trash'  => __( 'Reference not found in Trash', 'myfossil-specimen' ),
        );
        // }}}
        // {{{ Arguments
        $args = array(
            'label'               => __( 'myfs_reference', 'myfossil-specimen' ),
            'description'         => __( 'Bibliographic reference', 'myfossil-specimen' ),
            'labels'              => $labels,
            'supports'            => array( 'author', 'custom-fields' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'menu_icon'           => 'dashicons-welcome-learn-more'
        );
        // }}}
        register_post_type( 'myfs_reference', $args );
    }
    // }}}
    
    public function register_taxonomies() {
        $this->_ctax_init_taxa();
    }

    // {{{ Custom WordPress Taxonomies
    private function _ctax_init_taxa() {
        // {{{ Labels
        $labels = array(
            'name'                => __( 'Taxonomic Ranks', 'myfossil-specimen' ),
            'singular_name'       => __( 'Taxononic Rank', 'myfossil-specimen' ), 
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
        // }}}
        // {{{ Arguments
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
        // }}}
        register_taxonomy( 'myfs_taxa', array( 'myfs_taxon' ), $args );
        register_taxonomy_for_object_type( 'myfs_taxa', 'myfs_taxon' );
    }
    // }}}

    public function admin_menu() {
        $this->_cleanup_metaboxes();
        $this->_add_menu_separator();
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
    // }}}

    // {{{ Enqueues
    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    0.0.1
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in myFOSSIL_Specimen_Admin_Loader as all of the hooks are
         * defined in that particular class.
         *
         * The myFOSSIL_Specimen_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this class.
         */

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

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in myFOSSIL_Specimen_Admin_Loader as all of the hooks are
         * defined in that particular class.
         *
         * The myFOSSIL_Specimen_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this class.
         */

        wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) .
            'js/myfossil-specimen-admin.js', array( 'jquery' ),
            $this->version, false );

    }
    // }}}
}
