<?php 
/**
 * Class that sets up the Charitable Admin functionality.
 * 
 * @package     Charitable/Classes/Charitable_Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Admin' ) ) : 

/**
 * Charitable_Admin 
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Admin extends Charitable_Start_Object {

    /**
     * Set up the class. 
     * 
     * Note that the only way to instantiate an object is with the charitable_start method, 
     * which can only be called during the start phase. In other words, don't try 
     * to instantiate this object. 
     *
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {
        $this->load_dependencies();
        $this->attach_hooks_and_filters();
    }

    /**
     * Include admin-only files.
     * 
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function load_dependencies() {
        $admin_dir = charitable()->get_path( 'admin' );

        require_once( $admin_dir . 'charitable-core-admin-functions.php' );                 
        require_once( $admin_dir . 'class-charitable-meta-box-helper.php' );
        require_once( $admin_dir . 'class-charitable-admin-pages.php' );

        /* Campaigns */
        require_once( $admin_dir . 'campaigns/class-charitable-campaign-post-type.php' );
        
        /* Donations */
        require_once( $admin_dir . 'donations/class-charitable-donation-post-type.php' );       

        /* Settings */      
        require_once( $admin_dir . 'settings/class-charitable-settings.php' );
        require_once( $admin_dir . 'settings/class-charitable-general-settings.php' );
        require_once( $admin_dir . 'settings/class-charitable-email-settings.php' );
        require_once( $admin_dir . 'settings/class-charitable-gateway-settings.php' );
        require_once( $admin_dir . 'settings/class-charitable-licenses-settings.php' );
        require_once( $admin_dir . 'settings/class-charitable-advanced-settings.php' );
        require_once( $admin_dir . 'settings/charitable-settings-admin-hooks.php' );        

        /**
         * We are registering this object only for backwards compatibility. It
         * will be removed in or after Charitable 1.3.
         *
         * @deprecated
         */
        charitable()->register_object( Charitable_Settings::get_instance() );
    }

    /**
     * Sets up hook and filter callback functions for admin-only functionality.
     * 
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function attach_hooks_and_filters() {
        add_action( 'charitable_start', array( 'Charitable_Admin_Pages', 'charitable_start' ) );        
        add_action( 'charitable_start', array( 'Charitable_Campaign_Post_Type', 'charitable_start' ) );
        add_action( 'charitable_start', array( 'Charitable_Donation_Post_Type', 'charitable_start' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_filter( 'media_buttons_context', array( $this, 'remove_jquery_ui_styles_nf' ), 20 );
        add_filter( 'plugin_action_links_' . plugin_basename( charitable()->get_path() ),   array( $this, 'add_plugin_action_links' ) );
        add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
    }

    /**
     * Loads admin-only scripts and stylesheets. 
     *
     * @global  WP_Scripts $wp_scripts
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function admin_enqueue_scripts() {       
        global $wp_scripts;

        /* Menu styles are loaded everywhere in the Wordpress dashboard. */
        wp_register_style( 'charitable-admin-menu', charitable()->get_path( 'assets', false ) . 'css/charitable-admin-menu.css', array(), charitable()->get_version() );
        wp_enqueue_style( 'charitable-admin-menu' );

        /* The following styles are only loaded on Charitable screens. */
        $screen = get_current_screen();

        if ( in_array( $screen->id, $this->get_charitable_screens() ) ) {       
        
            wp_register_style( 'charitable-admin', charitable()->get_path( 'assets', false ) . 'css/charitable-admin.css', array(), charitable()->get_version() );
            wp_enqueue_style( 'charitable-admin' );

            wp_register_script( 'charitable-admin', charitable()->get_path( 'assets', false ) . 'js/charitable-admin.js', array( 'jquery-ui-datepicker', 'jquery-ui-tabs' ), charitable()->get_version(), false );      
            wp_enqueue_script( 'charitable-admin' );

            $localized_vars = apply_filters( 'charitable_localized_javascript_vars', array(
                'suggested_amount_placeholder'              => __( 'Amount', 'charitable' ),
                'suggested_amount_description_placeholder'  => __( 'Optional Description', 'charitable' )
            ) );

            wp_localize_script( 'charitable-admin', 'CHARITABLE', $localized_vars );            
        }
    }

    /**
     * Add custom links to the plugin actions. 
     *
     * @param   string[] $links
     * @return  string[]
     * @access  public
     * @since   1.0.0
     */
    public function add_plugin_action_links( $links ) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=charitable-settings' ) . '">' . __( 'Settings', 'charitable' ) . '</a>';
        return $links;
    }

    /**
     * Add Extensions link to the plugin row meta. 
     *
     * @param   string[] $links
     * @param   string $file        The plugin file 
     * @return  string[] $links
     * @access  public
     * @since   1.2.0
     */
    public function add_plugin_row_meta( $links, $file ) {
        if ( plugin_basename( charitable()->get_path() ) != $file ) {
            return $links;  
        }
        
        $extensions_link = esc_url( add_query_arg( array(
            'utm_source'   => 'plugins-page',
            'utm_medium'   => 'plugin-row',
            'utm_campaign' => 'admin' 
            ), 
            'https://wpcharitable.com/extensions/' 
        ) );

        $links[] = '<a href="' . $extensions_link . '">' . __( 'Extensions', 'charitable' ) . '</a>';

        return $links;
    }

    /**
     * Remove the jQuery UI styles added by Ninja Forms. 
     *
     * @return  void
     * @access  public
     * @since   1.2.0
     */
    public function remove_jquery_ui_styles_nf( $context ) {
        wp_dequeue_style( 'jquery-smoothness' );
        return $context;
    }

    /**
     * Returns an array of screen IDs where the Charitable scripts should be loaded. 
     *
     * @uses charitable_admin_screens
     * 
     * @return  array
     * @access  private
     * @since   1.0.0
     */
    private function get_charitable_screens() {
        return apply_filters( 'charitable_admin_screens', array(
            'campaign', 
            'donation', 
            'charitable_page_charitable-settings',
            'charitable_page_charitable-donations-table'
        ) );
    }   
}

endif;