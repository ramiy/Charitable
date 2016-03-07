<?php 

/**
 * Charitable Donation Functions. 
 *
 * Donation related functions.
 * 
 * @package     Charitable/Functions/Donation
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the given donation. 
 *
 * This will first attempt to retrieve it from the object cache to prevent duplicate objects.
 *
 * @param   int     $donation_id
 * @param   boolean $foce
 * @return  Charitable_Donation
 * @since   1.0.0
 */
function charitable_get_donation( $donation_id, $force = false ) {
    $donation = wp_cache_get( $donation_id, 'charitable_donation', $force );

    if ( ! $donation ) {
        $donation = new Charitable_Donation( $donation_id );
        wp_cache_set( $donation_id, $donation, 'charitable_donation' );            
    }

    return $donation;
}

/**
 * Get the gateway used for the donation.
 *
 * @param   int $donation_id
 * @return  string
 * @since   1.0.0
 */
function charitable_get_donation_gateway( $donation_id ) {
    return get_post_meta( $donation_id, 'donation_gateway', true );
}

/**
 * Returns the donation for the current request.
 * 
 * @return  Charitable_Donation
 * @since   1.0.0
 */
function charitable_get_current_donation() {
    return charitable_get_helper( 'request' )->get_current_donation();   
}

/**
 * Find and return a donation based on the given donation key.
 * 
 * @param   string $donation_key
 * @return  int|null
 * @since   1.4.0
 */
function charitable_get_donation_by_key( $donation_key ) {
    global $wpdb;

    $sql = "SELECT post_id 
            FROM $wpdb->postmeta 
            WHERE meta_key = 'donation_key' 
            AND meta_value = %s";

    return $wpdb->get_var( $wpdb->prepare( $sql, $donation_key ) );
}

/**
 * Checks for calls to our IPN. 
 *
 * This method is called on the init hook.
 *
 * IPNs in Charitable are structured in this way: charitable-listener=gateway
 *
 * @return  boolean True if this is a call to our IPN. False otherwise.
 * @since   1.4.0
 */
function charitable_ipn_listener() {
    if ( isset( $_GET[ 'charitable-listener' ] ) ) {

        $gateway = $_GET[ 'charitable-listener' ];
        do_action( 'charitable_process_ipn_' . $gateway );
        return true;
    }

    return false;
}

/**
 * Checks if this is happening right after a donation.
 *
 * This method is called on the init hook.
 *
 * @return  boolean 
 * @access  public
 * @since   1.4.0
 */
function charitable_is_after_donation() {
    $processor = get_transient( 'charitable_donation_' . charitable_get_session()->get_session_id() );

    if ( ! $processor ) {
        return;
    }

    do_action( 'charitable_after_donation', $processor );

    delete_transient( 'charitable_donation_' . charitable_get_session()->get_session_id() );
}