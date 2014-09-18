<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Roles' ) ) : 

/**
 * Roles and Capabilities for Charitable
 *
 * @class 		Charitable_Roles
 * @version		0.1
 * @package		Charitable/Classes/Core
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Roles {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the on_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.1
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
	 * @since 0.1
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Roles( $charitable );
	}

	/** 
	 * Sets up roles for Charitable. This is called by the install script. 
	 *
	 * @return void
	 * @since 0.1
	 */
	public function add_roles() {
		
	}

	/**
	 * Sets up capabilities for Charitable. This is called by the install script. 
	 *
	 * @return void
	 * @since 0.1
	 */
	public function add_caps() {
		
	}

	/**
	 * Removes roles. This is called upon deactivation.
	 *
	 * @return void
	 * @since 0.1
	 */
	public function remove_roles() {

	}
}

endif; // End class_exists check.