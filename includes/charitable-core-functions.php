<?php 

/**
 * Charitable Core Functions. 
 *
 * General core functions.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This returns the original Charitable object. 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return 	Charitable
 * @since 	1.0.0
 */
function charitable() {
    return Charitable::get_instance();
}

/**
 * This returns the value for a particular Charitable setting. 
 *
 * @param 	key 		$key
 * @param 	mixed 		$default 		The value to return if key is not set.
 * @return 	mixed
 * @since 	1.0.0
 */
function charitable_get_option( $key, $default = false ) {
	$settings = get_option( 'charitable_settings' );
	return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

/**
 * Displays a template. 
 *
 * @param 	string|array 	$template_name 		A single template name or an ordered array of template
 * @param 	bool 		 	$load 				If true the template file will be loaded if it is found.
 * @param 	bool 			$require_once 		Whether to require_once or require. Default true. Has no effect if $load is false. 
 * @return 	void
 * @since 	1.0.0
 */
function charitable_template( $template_name, $load = false, $require_once = true ) {
	new Charitable_Template( $template_name, $load, $require_once ); 
}

/**
 * Displays a template. 
 *
 * @param 	string 	$slug
 * @param 	string 	$name 		Optional name.
 * @return 	void
 * @since 	1.0.0
 */
function charitable_template_part( $slug, $name = "" ) {
	new Charitable_Template_Part( $slug, $name );
}

/**
 * Returns a helper class. 
 *
 * @param 	string $class_key
 * @return 	mixed
 * @since 	1.0.0
 */
function charitable_get_helper( $class_key ) {
	$class_name = 'Charitable_' . ucfirst( $class_key );
	
	if ( ! class_exists( $class_name ) ) {
		return false;
	}

	return charitable()->get_registered_object( $class_name );
}

/**
 * Returns the Charitable_Notices class instance.  
 *
 * @return 	Charitable_Notices
 * @since 	1.0.0
 */
function charitable_get_notices() {
	return Charitable_Notices::get_instance();	
}

/**
 * Return Charitable_Locations helper class. 
 *
 * @return 	Charitable_Locations
 * @since 	1.0.0
 */
function charitable_get_location_helper() {
	return charitable()->get_location_helper();
}

/**
 * Returns the current user's session object. 
 *
 * @return 	Charitable_Session
 * @since 	1.0.0
 */
function charitable_get_session() {
	return charitable()->get_registered_object( 'Charitable_Session' );
}

/**
 * Returns the current campaign. 
 *
 * @return 	Charitable_Campaign
 * @since 	1.0.0
 */
function charitable_get_current_campaign() {
	return charitable()->get_request()->get_current_campaign();
}

/**
 * Returns the current donation form.
 *
 * @return 	Charitable_Donation_Form_Interface|false
 * @since 	1.0.0
 */
function charitable_get_current_donation_form() {
	$campaign = charitable_get_current_campaign();
	return false === $campaign ? false : $campaign->get_donation_form();
}