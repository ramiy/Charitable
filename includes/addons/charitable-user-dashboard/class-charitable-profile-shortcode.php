<?php
/**
 * Class that manages the display and processing of the [charitable_profile] shortcode.
 *
 * @package		Charitable/Classes/Charitable_Profile_Shortcode
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Profile_Shortcode' ) ) : 

/**
 * Charitable_Profile_Shortcode
 *
 * @since 		1.0.0
 */
class Charitable_Profile_Shortcode {

	/**
	 * The shortcode's callback method. 
	 *
	 * This receives the user-defined attributes and passes the logic off to the class. 
	 *
	 * @param 	array 		$atts 		User-defined shortcode attributes.
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function shortcode( $atts ) {		

		if ( ! is_user_logged_in() ) {
			return wp_login_form( apply_filters( 'charitable_profile_shortcode_login_args', array() ) );
		}

		ob_start();

		$template = charitable_template( 'shortcodes/profile.php', false );
		$template->set_view_args( array( 
			'helper' => new Charitable_Profile_Shortcode( $atts ) 
		) );
		$template->render();

		return apply_filters( 'charitable_profile_shortcode', ob_get_clean() );

	}

	/**
	 * Create class object.
	 * 
	 * @param 	array 		$atts 		User-defined shortcode attributes.
	 * @access 	private
	 * @since	1.0.0
	 */
	private function __construct( $atts ) {

		shortcode_atts( array(
	        'foo' => 'something',
	        'bar' => 'something else',
	    ), $atts, 'charitable_profile' );

	}	
}

endif; // End class_exists check