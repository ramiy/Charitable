<?php
/**
 * Offline Payment Gateway 
 *
 * @class 		Charitable_Gateway_Offline
 * @version		1.0.0
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Gateway_Offline' ) ) : 

/**
 * Offline Payment Gateway 
 *
 * @since		1.0.0
 */
class Charitable_Gateway_Offline extends Abstract_Charitable_Gateway {
	
	/**
	 * @var 	string 		The gateway name.
	 */
	const GATEWAY_NAME = 'Offline Payments';

	/**
	 * @var 	string 		The gateway ID.
	 */
	const GATEWAY_ID = 'offline';

	/**
	 * Send the donation/donor off to the gateway.  
	 *
	 * @param 	Charitable_Campaign 	$campaign
	 * @param 	int 					$donation_id
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function send_donation_to_gateway( $campaign, $donation_id ) {

	}
}

endif; // End class_exists check