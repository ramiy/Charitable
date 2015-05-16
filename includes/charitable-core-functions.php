<?php 

/**
 * Charitable Core Functions. 
 *
 * General core functions.
 * 
 * @package 	Charitable/Functions/Core
 * @version     1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
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
 * @param 	mixed		$key 			Accepts an array of strings or a single string.
 * @param 	mixed 		$default 		The value to return if key is not set.
 * @param 	array 		$settings 		Optional. Used when $key is an array.
 * @return 	mixed
 * @since 	1.0.0
 */
function charitable_get_option( $key, $default = false, $settings = array() ) {
	if ( empty( $settings ) ) {
		$settings = get_option( 'charitable_settings' );		
	}

	if ( is_array( $key ) ) {
		$current_key = current( $key );

		/* Key does not exist */
		if ( ! isset( $settings[ $current_key ] ) ) {		

			return $default;

		}
		else {

			array_shift( $key );

			if ( empty( $key ) ) {
				
				return $settings[ $current_key ];

			}
			else {

				return charitable_get_option( $key, $default, $settings[ $current_key ] );

			}			
		}
	}
	else {

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;

	}
}

/**
 * Returns a helper class. 
 *
 * @param 	string $class_key
 * @return 	mixed
 * @since 	1.0.0
 */
function charitable_get_helper( $class_key ) {
	if ( false !== strpos( $class_key, '_' ) ) {
		$class_words = str_replace( '_', ' ', $class_key );
	}
	else {
		$class_words = $class_key;
	}

	$class_words = ucwords( $class_words );
	$class_name = 'Charitable_' . str_replace( ' ', '_', $class_words );
	
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
 * Return currency helper class.  
 *
 * @return  Charitable_Currency
 * @since   1.0.0
 */
function charitable_get_currency_helper() {
	return charitable()->get_currency_helper();
}

/**
 * Returns the current user's session object. 
 *
 * @return 	Charitable_Session
 * @since 	1.0.0
 */
function charitable_get_session() {
	return charitable_get_helper( 'session' );
}

/**
 * Returns the given campaign. 
 *
 * @param 	int 	$campaign_id
 * @return 	Charitable_Campaign
 * @since 	1.0.0
 */
function charitable_get_campaign( $campaign_id ) {
	return new Charitable_Campaign( $campaign_id );
}

/**
 * Returns the current campaign. 
 *
 * @return 	Charitable_Campaign
 * @since 	1.0.0
 */
function charitable_get_current_campaign() {
	return charitable_get_helper( 'request' )->get_current_campaign();
}

/**
 * Returns the current campaign ID.
 *
 * @return 	int
 * @since 	1.0.0
 */
function charitable_get_current_campaign_id() {
	return charitable_get_helper( 'request' )->get_current_campaign_id();
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

/**
 * Returns the provided array as a HTML element attribute. 
 *
 * @param 	array 		$args
 * @return 	string
 * @since 	1.0.0
 */
function charitable_get_action_args( $args ) {
	return sprintf( "data-charitable-args='%s'", json_encode( $args ) );
}