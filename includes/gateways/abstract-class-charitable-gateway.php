<?php
/**
 * Gateway abstract model 
 *
 * @class 		Charitable_Gateway
 * @abstract
 * @version		1.0.0
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Abstract_Charitable_Gateway' ) ) : 

/**
 * Charitable_Gateway
 *
 * @since		1.0.0
 */
abstract class Abstract_Charitable_Gateway {	
	
	/**
	 * Send a donation to the gateway. 
	 *
	 * @param 	Charitable_Campaign 	$campaign
	 * @param 	int 					$donation_id
	 */
	abstract public function send_donation_to_gateway( $campaign, $donation_id );

}

endif; // End class_exists check