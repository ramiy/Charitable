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

	if ( ! did_action( 'charitable_start' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'charitable_get_donation should not be called before the charitable_start action.', 'charitable' ), '1.0' );
		return false;
	}

    $donation = wp_cache_get( $donation_id, 'charitable_donation', $force );

    if ( ! $donation ) {
        $donation = charitable()->donation_factory->get_donation( $donation_id );
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
   
/**
 * Returns whether the donation status is valid. 
 *
 * @return  boolean
 * @since   1.3.0
 */
function charitable_is_valid_donation_status( $status ) {
    return array_key_exists( $status, charitable_get_valid_donation_statuses() );
}

/**
 * Returns the donation statuses that signify a donation was complete. 
 *
 * By default, this is just 'charitable-completed'. However, 'charitable-preapproval' 
 * is also counted. 
 *
 * @return  string[]
 * @since   1.3.0
 */
function charitable_get_approval_statuses() {
    return apply_filters( 'charitable_approval_donation_statuses', array( 'charitable-completed' ) );
}

/**
 * Returns whether the passed status is an confirmed status. 
 *
 * @return  boolean
 * @since   1.3.0
 */
function charitable_is_approved_status( $status ) {
    return in_array( $status, charitable_get_valid_donation_statuses() );
}

/**
 * Return array of valid donations statuses. 
 *
 * @return  array
 * @since   1.3.0
 */
function charitable_get_valid_donation_statuses() {
    return apply_filters( 'charitable_donation_statuses', array( 
        'charitable-completed'  => __( 'Paid', 'charitable' ),
        'charitable-pending'    => __( 'Pending', 'charitable' ),           
        'charitable-failed'     => __( 'Failed', 'charitable' ),
        'charitable-cancelled'  => __( 'Cancelled', 'charitable' ),
        'charitable-refunded'   => __( 'Refunded', 'charitable' )
    ) );
}   

/**
 * Add a message to the donation log. 
 *
 * @param   string      $message
 * @return  void
 * @since   1.0.0
 */
function charitable_update_donation_log( $donation_id, $message ) {

    $log = charitable_get_donation_log( $donation_id );

    $log[] = array( 
        'time'      => time(), 
        'message'   => $message
    );

    update_post_meta( $donation_id, '_donation_log', $log );
}

/**
 * Get a donation's log.  
 *
 * @return  array
 * @since   1.0.0
 */
function charitable_get_donation_log( $donation_id ) {
    $log = get_post_meta( $donation_id, '_donation_log', true );;

    return is_array( $log ) ? $log : array();
}

/**
 * Sanitize meta values before they are persisted to the database. 
 *
 * @param   mixed   $value
 * @param   string  $key
 * @return  mixed
 * @since   1.0.0
 */
function charitable_sanitize_donation_meta( $value, $key ) {
    if ( 'donation_gateway' == $key ) {         
        if ( empty( $value ) || ! $value ) {
            $value = 'manual';
        }           
    }

    return apply_filters( 'charitable_sanitize_donation_meta-' . $key, $value );
}

/**
 * Flush the donations cache for every campaign receiving a donation. 
 *
 * @param   int $donation_id
 * @return  void
 * @since   1.0.0
 */
function charitable_flush_campaigns_donation_cache( $donation_id ) {
    $campaign_donations = charitable_get_table( 'campaign_donations' )->get_donation_records( $donation_id );

    foreach ( $campaign_donations as $campaign_donation ) {
        Charitable_Campaign::flush_donations_cache( $campaign_donation->campaign_id );
    }
}