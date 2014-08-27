<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Query' ) ) :

/** 
 * Charitable Query. 
 *  
 * @class Charitable_Query
 * @version		0.1
 * @package		Charitable/Classes/Charitable_Query
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Query {

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
	 * @since 0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
	
		// add_action('init', array(&$this, 'init'));

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

		new Charitable_Query( $charitable );
	}
}

endif; // End class_exists check 