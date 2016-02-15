<?php
/**
 * The class that is responsible for registering the Welcome page.
 *
 * @package     Charitable/Classes/Charitable_Welcome_Page
 * @version     1.3.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Welcome_Page' ) ) : 

/**
 * Charitable_Welcome_Page
 *
 * @since       1.3.0
 */
class Charitable_Welcome_Page {

    /**
     * @var     Charitable_Welcome_Page
     * @access  private
     * @static
     * @since   1.3.0
     */
    private static $instance = null;

    /**
     * Create class object. Private constructor. 
     * 
     * @access  private
     * @since   1.3.0
     */
    private function __construct() {
    }

    /**
     * Create and return the class object.
     *
     * @access  public
     * @static
     * @since   1.3.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Welcome_Page();            
        }

        return self::$instance;
    }

    /**
     * Register the page. 
     *
     * @return  void
     * @access  public
     * @since   1.3.0
     */
    public function register_page() {
        add_dashboard_page( 
            __( 'Welcome to Charitable', 'charitable' ), 
            __( 'Welcome to Charitable', 'charitable' ),
            'manage_charitable_settings', 
            'charitable-welcome',
            array( $this, 'render_page' ) 
        );   
    }

    /**
     * Remove the page from the dashboard menu. 
     *
     * @return  void
     * @access  public
     * @since   1.3.0
     */
    public function remove_page_from_menu() {
        remove_submenu_page( 'index.php', 'charitable-welcome' );
    }

    /**
     * Add custom CSS styles to the admin page. 
     *
     * @return  void
     * @access  public
     * @since   1.3.0
     */
    public function add_custom_styles() {
        if ( ! $this->is_welcome_page() ) {
            return;
        }

        wp_enqueue_style( 'charitable-admin-pages' );
    }

    /**
     * Render the page. 
     *
     * @return  void
     * @access  public
     * @since   1.3.0
     */
    public function render_page() {
        charitable_admin_view( 'welcome-page/page', array( 'page' => $this ) );
    }

    /**
     * Returns true if this is the welome page. 
     *
     * @return  boolean
     * @access  public
     * @since   1.3.0
     */
    public function is_welcome_page() {
        return isset( $_GET[ 'page' ] ) && 'charitable-welcome' == $_GET[ 'page' ];
    }

    /**
     * Returns true if we're looking at the welcome page after a version upgrade. 
     *
     * @return  boolean
     * @access  public
     * @since   1.3.0
     */
    public function is_after_upgrade() {
        if ( ! $this->is_welcome_page() ) {
            return false;
        }

        return isset( $_GET[ 'is-upgrade' ] ) && $_GET[ 'is-upgrade' ];
    }

    /**
     * Return the page title. 
     *
     * @return  string
     * @access  public
     * @since   1.3.0
     */
    public function get_page_title() {
        if ( $this->is_after_upgrade() ) {
            return __( 'Thanks for Upgrading Charitable', 'charitable' );
        }   

        return __( 'Thanks for Installing Charitable', 'charitable' );
    }
}

endif; // End class_exists check