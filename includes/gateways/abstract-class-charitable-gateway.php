<?php
/**
 * Gateway abstract model 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Abstract_Charitable_Gateway
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Abstract_Charitable_Gateway' ) ) : 

/**
 * Charitable_Gateway
 *
 * @abstract
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