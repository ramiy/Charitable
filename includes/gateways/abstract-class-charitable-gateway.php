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
	 * @var string Name of the gateway
	 */
	private $name = false;

	/**
	 * Returns the name of the gateway.
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
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