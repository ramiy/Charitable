<?php
/**
 * The class that is responsible for responding to donation events. 
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Donation_Controller
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Controller' ) ) : 

/**
 * Charitable Donation Controller.
 *
 * @since       1.0.0
 */
class Charitable_Donation_Controller {

    /**
     * Executed when a user first clicks the Donate button on a campaign. 
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function start_donation() {
        $campaign_id = charitable_get_current_campaign_id();

        if ( ! $campaign_id ) {
            return;
        }       

        /* Save the donation in the session */
        charitable_get_session()->add_donation( $campaign_id, 0 );
        
        $donations_url = charitable_get_permalink( 'campaign_donation_page', array( 'campaign_id' => $campaign_id ) );
        
        wp_redirect( $donations_url );

        die();
    }

    /**
     * Save a donation.
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function make_donation() {
        $campaign = charitable_get_current_campaign();

        if ( ! $campaign ) {
            return;
        }

        /**
         * @hook charitable_before_save_donation
         */
        do_action( 'charitable_before_save_donation', $campaign );

        /**
         * Save the donation using the campaign's donation form object.
         */
        $form = $campaign->get_donation_form();
        $donation_id = $form->save_donation();

        /**
         * @hook charitable_after_save_donation
         */
        do_action( 'charitable_after_save_donation', $donation_id, $campaign, $form );
    }

    /**
     * Add a donation with AJAX. 
     *
     * @return  json
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function ajax_make_donation() {  
        if ( ! isset( $_POST[ 'campaign_id' ] ) ) {
            wp_send_json_error( new WP_Error( 'missing_campaign_id', __( 'Campaign ID was not found. Unable to create donation.', 'charitable' ) ) );
        }

        $form_action = isset( $_POST[ 'form_action' ] ) ? $_POST[ 'form_action' ] : 'make_donation';

        $campaign = new Charitable_Campaign( $_POST[ 'campaign_id' ] );
        
        /**
         * @hook    charitable_before_save_ajax_donation
         */
        do_action( 'charitable_before_save_ajax_donation', $campaign );

        if ( 'make_donation_streamlined' == $form_action ) {

            $form = new Charitable_Donation_Amount_Form( $campaign );
            $donation_id = $form->save_donation();

        }
        else {

            $form = $campaign->get_donation_form();
            $donation_id = $campaign->get_donation_form()->save_donation();
        }

        /**
         * @hook    charitable_after_save_ajax_donation
         */
        do_action( 'charitable_after_save_ajax_donation', $donation_id, $campaign, $form );

        $data = apply_filters( 'charitable_ajax_make_donation_data', array(
            'donation_id' => $donation_id
        ), $donation_id, $campaign );

        wp_send_json_success( $data );      
    }

    /**
     * Save a donation.
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function make_donation_streamlined() {
        $campaign = charitable_get_current_campaign();

        if ( ! $campaign ) {
            return;
        }

        $form = new Charitable_Donation_Amount_Form( $campaign );
        $form->save_donation();
    } 

    /**
     * Send the donation/donor off to the gateway.  
     *
     * @param   int     $donation_id
     * @param   Charitable_Campaign $campaign
     * @param   Charitable_Form $form
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function send_donation_to_gateway( $donation_id, $campaign, $form ) {
        $gateway = charitable_get_donation_gateway( $donation_id );

        do_action( 'charitable_make_donation_' . $gateway, $donation_id, $campaign, $form );
    }
}

endif; // End class_exists check.