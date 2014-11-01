<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Gateway_Paypal' ) ) : 

/**
 * Paypal Payment Gateway 
 *
 * @class 		Charitable_Gateway_Paypal
 * @version		1.0.0
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Gateway_Paypal extends Abstract_Charitable_Gateway 
	implements Charitable_Gateway_Interface {
	
	/**
	 * @var string The gateway name.
	 */
	private $name = 'Paypal';
}

endif; // End class_exists check