<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Donation' ) ) : 

/**
 * Donation model
 *
 * @class 		Charitable_Donation
 * @version		0.1
 * @package		Charitable/Classes/Donation
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Donation {
	
	/**
	 * @var 	Charitable_Donations_DB 
	 * @access 	private
	 */
	private $db;

	/**
	 * @var 	Object The database record for this donation.
	 */
	private $data;

	/**
	 * @var 	Charitable_Gateway_Interface The payment gateway used to process the donation.
	 */
	private $gateway;

	/**
	 * @var 	Charitable_Campaign The campaign that was donated to.
	 */
	private $campaign;

	/**
	 * @var 	WP_User The WP_user object of the person who donated.
	 */
	private $user;

	/**
	 * Instantiate a new donation object based off the ID.
	 * 
	 * @param 	int $donation_id The donation ID.
	 * @return 	void
	 * @access 	public
	 * @since 	0.1
	 */
	public function __construct( $donation_id ) {
		$this->db = new Charitable_Donations_DB();
		$this->data = $this->db->get( $donation_id );
	}

	/**
	 * The name of the gateway used to process the donation.
	 *
	 * @return 	string The name of the gateway.
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_gateway() {
		
	}

	/**
	 * The amount donated on this donation.
	 *
	 * @return 	float
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_amount() {
		
	}

	/**
	 * The amount donated on this donation.
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_status() {
		
	} 

	/**
	 * Returns the donation ID. 
	 * 
	 * @return 	int
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_donation_id() {
		
	}

	/**
	 * Indicates whether the amount donated was a suggested amount or not.
	 *
	 * @return 	bool
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_is_preset_amount() {
		
	}

	/**
	 * Returns the customer note attached to the donation.
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_note() {
		
	}
	
	/**
	 * Inserts a new donation. 
	 *
	 * @param 	array $args
	 * @return 	int $donation_id
	 * @access 	public
	 * @static
	 * @since 	0.1
	 */
	public static function insert( array $args ) {
		$db = new Charitable_Donations_DB();
		return $db->add( $args );
	}
}

endif; // End class_exists check