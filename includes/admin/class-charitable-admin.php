<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Admin' ) ) : 

/**
 * Charitable Admin.
 *
 * @class 		Charitable_Admin 
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin
 * @version     0.0.1
 */
final class Charitable_Admin {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->wp_charitable = $charitable;
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
	 * @since 0.0.1
	 */
	public static function charitable_admin_start(Charitable $charitable) {
		if ( ! $charitable->is_admin_start() ) {
			return;
		}

		new Charitable_Admin( $charitable );
	}
}

endif;