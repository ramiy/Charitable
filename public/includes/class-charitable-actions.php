<?php
/**
 * Handle form submissions in the Charitable front-end interface. 
 *
 * @class 		Charitable_Actions
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Actions
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Actions' ) ) : 

/**
 * Charitable_Actions class.
 *
 * @since		1.0.0
 * @final
 */
final class Charitable_Actions {

	/**
	 * @var 	Charitable $charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
	
		add_action('init', array( $this, 'init' ) );

		// The main Charitable class will save the one instance of this object.
		$this->charitable->register_object( $this );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @see 	charitable_start hook
	 * 
	 * @param 	Charitable $charitable 
	 * @return 	void
	 * @static 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Actions( $charitable );
	}

	/**
	 * Checks the current request for two events: 
	 *
	 * 1. Displaying a campaign's donation form.
	 * 2. Saving a donation.
	 *
	 * @see 	init hook
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function init() {
		if ( isset( $_POST['charitable-action'] ) ) {
			$action = $_POST['charitable-action'];

			switch ( $action ) {
				/**
				 * Fired on donation.
				 */
				case 'donate-now' :
					$this->donation_handler();
					break;
			}
		}
	}

	/**
	 * Executed when a user first clicks the Donate button on a campaign. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function donation_handler() {
		/**
		 * A campaign ID must be set. 
		 */
		if ( ! isset( $_POST['campaign-id'] ) ) {
			return;
		}

		$campaign_id = absint( $_POST['campaign-id'] );

		/**
		 * The ID must be for a campaign. 
		 */
		if ( 'campaign' !== get_post_type( $campaign_id ) ) {
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

		$donations_url = charitable_get_helper( 'pages' )->get_page_url( 'donation-form' );
		
		wp_redirect( $donations_url );
	}

	/**
	 * Load a campaign's donation form.
	 *
	 * @global 	$post WP_Post
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function load_donation_form() {
		global $post;

		$campaign = new Charitable_Campaign( $post );

		/**
		 * Render the donation form.
		 */
		$donation_form = $campaign->get_donation_form();
		$donation_form->render();
	}

	/**
	 * Save a donation.
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function save_donation() {
		global $post;

		$campaign = new Charitable_Campaign( $post );

		/**
		 * Load the donation form object and ask it to save the donation. 
		 */
		$donation_form = $campaign->get_donation_form();
		$donation_form->save_donation();
	}
}

endif; // End class_exists check.