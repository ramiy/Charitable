<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! interface_exists( 'Charitable_Gateway_Interface' ) ) : 

/**
 * Gateway interface
 *
 * @interface 	Charitable_Gateway_Interface
 * @abstract
 * @version		0.0.1
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */
interface Charitable_Gateway_Interface {
}

endif; // End interface_exists check