<?php
/**
 * Handle donation actions.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Donation_Actions
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Actions' ) ) : 

/**
 * Charitable Donation actions.
 *
 * @since		1.0.0
 * @final
 */
final class Charitable_Donation_Actions extends Charitable_Start_Object {

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
		// add_action( 'init', 						array( $this, 'handle_form_submissions' ) );
		// add_action( 'charitable_start_donation', 	array( $this, 'start_donation' ), 1 );
		add_action( 'charitable_start_donation', 	array( $this, 'start_donation' ) );
		add_action( 'charitable_make_donation', 	array( $this, 'make_donation' ) );

		// add_action( 'charitable_make_donation', 	array( $this, 'save_pending_donation' ), 1 );
		// add_action( 'charitable_make_donation', 	array( $this, 'send_to_gateway' ), 2 );

		do_action( 'charitable_donation_actions_start', $this );
	}

	/**
	 * Returns the campaign ID in the current request. 
	 *
	 * This also validates that a campaign ID was passed, and 
	 * that the ID passed belonged to a campaign.
	 *
	 * @return 	false if invalid. int if valid. 
	 * @access 	public
	 * @since 	1.0.0
	 */ 
	public function get_campaign_from_request() {
		/**
		 * A campaign ID must be set. 
		 */
		if ( ! isset( $_POST['campaign_id'] ) ) {
			return false;
		}

		$campaign_id = absint( $_POST['campaign_id'] );

		/**
		 * The ID must be for a campaign. 
		 */
		if ( 'campaign' !== get_post_type( $campaign_id ) ) {
			return false;
		} 

		return $campaign_id;
	}

	/**
	 * Executed when a user first clicks the Donate button on a campaign. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function start_donation() {
	
		$campaign_id = $this->get_campaign_from_request();

		if ( false === $campaign_id ) {
			return;
		}

		/**
		 * Create or update the donation object in the session, with the current campaign ID.
		 */
		$session = charitable_get_session();
		$donation = $session->get( 'donation' );
		
		if ( false === $donation ) {
			$donation = new Charitable_Session_Donation();			
		}

		$donation->set( 'campaign_id', $campaign_id ); 
		$session->set( 'donation', $donation );

		$donations_url = charitable_get_helper( 'pages' )->get_page_url( 'campaign-donation-page', array( 'campaign_id' => $campaign_id ) );
		
		wp_redirect( $donations_url );
	}

	/**
	 * Save a donation.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function make_donation() {
		$campaign_id = $this->get_campaign_from_request();

		if ( false === $campaign_id ) {
			return;
		}

		$campaign = new Charitable_Campaign( $campaign_id );

		/**
		 * @hook 	charitable_before_save_donation
		 */
		do_action( 'charitable_before_save_donation', $campaign );

		/**
		 * Save the donation using the campaign's donation form object.
		 */
		$donation_id = $campaign->get_donation_form()->save_donation();

		/**
		 * @hook 	charitable_after_save_donation
		 */
		do_action( 'charitable_after_save_donation', $campaign, $donation_id );
	}
}

endif; // End class_exists check.