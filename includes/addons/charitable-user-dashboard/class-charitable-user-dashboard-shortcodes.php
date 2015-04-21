<?php
/**
 * Class responsible for registering the shortcodes that are part of Charitable.
 *
 * @package		Charitable/Classes/Charitable_User_Dashboard_Shortcodes
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_User_Dashboard_Shortcodes' ) ) : 

/**
 * Charitable_User_Dashboard_Shortcodes
 *
 * @since 		1.0.0
 */
class Charitable_User_Dashboard_Shortcodes {

	/**
	 * Load the class. 
	 *
	 * @return  boolean 	True if class is loaded. False otherwise.
	 * @access  public
	 * @static
	 * @since   1.0.0
	 */
	public static function start() {
		if ( 'charitable_user_dashboard_start' !== current_filter() ) {
			return false;
		}

		new Charitable_User_Dashboard_Shortcodes();

		return true;
	}	

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {

		$this->register_shortcodes();
	}

	/**
	 * Register shortcodes.
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function register_shortcodes() {		
		add_shortcode( 'charitable_profile',    	array( $this, 'charitable_profile_shortcode' ) );
        add_shortcode( 'charitable_login',    		array( $this, 'charitable_login_shortcode' ) );
        add_shortcode( 'charitable_registration',  	array( $this, 'charitable_registration_shortcode' ) );
	}

    /**
     * The shortcode's callback method. 
     *
     * This receives the user-defined attributes and passes the logic off to the class. 
     *
     * @param   array       $atts       User-defined shortcode attributes.
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public function charitable_profile_shortcode( $atts ) {     

        if ( ! is_user_logged_in() ) {
            return wp_login_form( apply_filters( 'charitable_profile_shortcode_login_args', array() ) );
        }

        $args = shortcode_atts( array(), $atts, 'charitable_profile' );     

        ob_start();

        $template = charitable_template( 'shortcodes/profile.php', false );
        $template->set_view_args( array( 
            'form' => new Charitable_Profile_Form( $args ) 
        ) );
        $template->render();

        return apply_filters( 'charitable_profile_shortcode', ob_get_clean() );
    }	
	
	/**
     * The shortcode's callback method. 
     *
     * This receives the user-defined attributes and passes the logic off to the class. 
     *
     * @param   array       $atts       User-defined shortcode attributes.
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public function charitable_login_shortcode( $atts ) {     
    	global $wp;

        $args = shortcode_atts( array(), $atts, 'charitable_login' );     

        ob_start();

        $template = charitable_template( 'shortcodes/login.php', false );
        $template->set_view_args( array(
            'form' => new Charitable_Login_Form( $args ) 
        ) );
        $template->render();

        return apply_filters( 'charitable_login_shortcode', ob_get_clean() );
    }

	/**
     * The shortcode's callback method. 
     *
     * This receives the user-defined attributes and passes the logic off to the class. 
     *
     * @param   array       $atts       User-defined shortcode attributes.
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public function charitable_registration_shortcode( $atts ) {     

        $args = shortcode_atts( array(), $atts, 'charitable_registration' );     

        ob_start();

        $template = charitable_template( 'shortcodes/registration.php', false );
        $template->set_view_args( array( 
            'form' => new Charitable_Registration_Form( $args ) 
        ) );
        $template->render();

        return apply_filters( 'charitable_registration_shortcode', ob_get_clean() );
    }
}

endif; // End class_exists check