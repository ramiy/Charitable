<?php
/**
 * Charitable widgets class. 
 *
 * Registers custom widgets for Charitable.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Widgets
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Widgets' ) ) : 

/**
 * Charitable_Widgets
 *
 * @final
 * @since 		1.0.0
 */
final class Charitable_Widgets extends Charitable_Start_Object {

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the on_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {
		$this->include_widgets();

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Include widget files. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function include_widgets() {
		require_once( charitable()->get_path( 'includes' ) . 'widgets/class-charitable-campaigns-widget.php' );
	}

	/**
	 * Register widgets.
	 *
	 * @see 	widgets_init hook
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function register_widgets() {
		register_widget( 'Charitable_Campaigns_Widget' );
	}
}

endif; // End class_exists check.