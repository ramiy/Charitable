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
function get_charitable() {
    return Charitable::get_instance();
}

/**
 * Displays a template. 
 *
 * @since 	1.0.0
 */
function charitable_get_template() {
	
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

	return get_charitable()->get_registered_object( $class_name );
}

/**
 * Returns the current user's session object. 
 *
 * @return 	Charitable_Session
 * @since 	1.0.0
 */
function charitable_get_session() {
	return get_charitable()->get_registered_object( 'Charitable_Session' );
}

/**
 * Returns the current campaign. 
 *
 * @return 	Charitable_Campaign
 * @since 	1.0.0
 */
function charitable_get_current_campaign() {
	return get_charitable()->get_request()->get_current_campaign();
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