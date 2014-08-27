<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Widgets' ) ) : 

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class 		Charitable_Widgets
 * @version		0.1
 * @package		Charitable/Classes/Core
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Widgets {

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
	
		$this->include_widgets();

		add_action( 'widgets_init', array( &$this, 'register_widgets' ) );

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

		new Charitable_Widgets( $charitable );
	}

	/**
	 * Include widget files. 
	 *
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function include_widgets() {
		require_once( $this->charitable->get_includes_path() . 'widgets/class-charitable-campaigns-widget.php' );
	}

	/**
	 * Register widgets
	 *
	 * @see widgets_init hook
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function register_widgets() {
		register_widget( 'Charitable_Campaigns_Widget' );
	}
}

endif; // End class_exists check.