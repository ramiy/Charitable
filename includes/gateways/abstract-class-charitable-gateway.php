<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Gateway' ) ) : 

/**
 * Gateway abstract model 
 *
 * @class 		Charitable_Gateway
 * @abstract
 * @version		0.0.1
 * @package		Charitable/Classes/Gateway
 * @category	Class
 * @author 		Studio164a
 */
abstract class Abstract_Charitable_Gateway {
	
	/**
	 * @var string Name of the gateway
	 */
	private $name = false;

	/**
	 * Returns the name of the gateway.
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */	
	public function get_name() {
		if ( $this->name === false ) {
			throw new Exception( sprintf('%: %s'), 
				__( 'Gateway does not declare its name', 'charitable' ), 
				get_class( $this ) 
			);
		}

		return $this->name;
	}
}

endif; // End class_exists check