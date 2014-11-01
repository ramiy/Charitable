<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Donation_Actions' ) ) : 

/**
 * Charitable Donation actions.
 *
 * @class 		Charitable_Donation_Actions
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Donation_Actions
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Donation_Actions {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
			

		// The main Charitable class will save the one instance of this object.
		$this->charitable->register_object( $this );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @see charitable_start hook
	 * 
	 * @param Charitable $charitable 
	 * @return void
	 * @static 
	 * @access public
	 * @since 1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Donation_Actions( $charitable );
	}

	/**
	 * Checks the current request for two events: 
	 *
	 * 1. Displaying a campaign's donation form.
	 * 2. Saving a donation.
	 *
	 * @see init hook
	 *
	 * @return void
	 * @access public
	 * @since 1.0.0
	 */
	public function init() {
		// global $wp_query;
		// echo '<pre>'; print_r( $wp_query );
		// die; 
			// echo '<pre>'; print_r( query_vars() );
			// 	die; 

		/**
		 *  
		 */
		if ( get_query_var( 'donate', false ) ) {

		}

		/**
		 * 
		 */
	}

	/**
	 * Load a campaign's donation form.
	 *
	 * @global $post WP_Post
	 * @return void
	 * @access private
	 * @since 1.0.0
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
	 * @return void
	 * @access private
	 * @since 1.0.0
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