<?php 
/**
 * Charitable Public class. 
 *
 * @package 	Charitable/Classes/Charitable_Public
 * @version     1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Public' ) ) : 

/**
 * Charitable Public class. 
 *
 * @final
 * @since 	    1.0.0
 */
final class Charitable_Public {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Public|null
     * @access  private
     * @static
     */
    private static $instance = null;    

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Public
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Public();
        }

        return self::$instance;
    }

	/**
	 * Set up the class. 
	 *
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct() {				
        add_action( 'after_setup_theme', array( $this, 'load_template_files' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts') );
        add_filter( 'post_class', array( $this, 'campaign_post_class' ) );
        add_filter( 'comments_open', array( $this, 'disable_comments_on_application_pages' ), 10, 2 );

        /**
         * We are registering this object only for backwards compatibility. It
         * will be removed in or after Charitable 1.3.
         *
         * @deprecated
         */
        charitable()->register_object( Charitable_Session::get_instance() );
        charitable()->register_object( Charitable_Templates::get_instance() );

		do_action( 'charitable_public_start', $this );
	}    

    /**
     * Load the template functions after theme is loaded. 
     *
     * This gives themes time to override the functions. 
     *
     * @return  void
     * @access  public
     * @since   1.2.3
     */
    public function load_template_files() {
        require_once( 'charitable-template-functions.php' );
        require_once( 'charitable-template-hooks.php' );
    }

	/**
	 * Loads public facing scripts and stylesheets. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function wp_enqueue_scripts() {        
		$vars = apply_filters( 'charitable_javascript_vars', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'currency_format_num_decimals' => esc_attr( charitable_get_option( 'decimal_count', 2 ) ),
            'currency_format_decimal_sep' => esc_attr( charitable_get_option( 'decimal_separator', '.' ) ),
            'currency_format_thousand_sep' => esc_attr( charitable_get_option( 'thousands_separator', ',' ) ),
            'currency_format' => esc_attr( charitable_get_currency_helper()->get_accounting_js_format() ), // For accounting.js
		) );

        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        wp_register_script( 'accounting', charitable()->get_path( 'assets', false ) . 'js/libraries/accounting'. $suffix . '.js', array( 'jquery' ), charitable()->get_version(), true );
        wp_enqueue_script( 'accounting' );

		wp_register_script( 'charitable-script', charitable()->get_path( 'assets', false ) . 'js/charitable'. $suffix . '.js', array( 'jquery' ), charitable()->get_version(), true );
        wp_localize_script( 'charitable-script', 'CHARITABLE_VARS', $vars );
        wp_enqueue_script( 'charitable-script' );

		wp_register_style( 'charitable-styles', charitable()->get_path( 'assets', false ) . 'css/charitable' . $suffix .'.css', array(), charitable()->get_version() );
		wp_enqueue_style( 'charitable-styles' );

		/* Lean Modal is registered but NOT enqueued yet. */
		if ( 'modal' == charitable_get_option( 'donation_form_display', 'separate_page' ) ) {
			wp_register_script( 'lean-modal', charitable()->get_path( 'assets', false ) . 'js/libraries/leanModal' . $suffix . '.js', array( 'jquery' ), charitable()->get_version() );
			wp_register_style( 'lean-modal-css', charitable()->get_path( 'assets', false ) . 'css/modal' . $suffix .'.css', array(), charitable()->get_version() );
		}
	}

    /**
     * Adds custom post classes when viewing campaign. 
     *
     * @return  string[] 
     * @access  public
     * @since   1.0.0
     */
    public function campaign_post_class( $classes ) {
        $campaign = charitable_get_current_campaign();

        if ( ! $campaign ) {
        	return $classes;
        }

        $classes[] = $campaign->has_goal() ? 'campaign-has-goal' : 'campaign-has-no-goal';
        $classes[] = $campaign->is_endless() ? 'campaign-is-endless' : 'campaign-has-end-date';
        return $classes;
    }

    /**
     * Disable comments on application pages like the donation page.
     *
     * @param   boolean $open
     * @param   int $post_id
     * @return  boolean
     * @access  public
     * @since   1.3.0
     */
    public function disable_comments_on_application_pages( $open, $post_id ) {
        /* If open is already false, just hit return. */
        if ( ! $open ) {
            return $open;
        }

       if ( charitable_is_page( 'campaign_donation_page' ) 
            || charitable_is_page( 'campaign_widget_page' ) 
            || charitable_is_page( 'donation_receipt_page' ) 
            || charitable_is_page( 'donation_processing_page' ) ) {
            $open = false;
        }

        return $open;
    }
}

endif;