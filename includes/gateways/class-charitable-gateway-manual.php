<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Gateway_Manual' ) ) : 

/**
 * Manaul Payment Gateway 
 *
 * @class 		Charitable_Gateway_Manual
 * @version		0.1
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Gateway_Manual extends Abstract_Charitable_Gateway
	implements Charitable_Gateway_Interface {
	
	/**
	 * @var string The gateway name.
	 */
	private $name = 'Manual Payment';
}

endif; // End class_exists check