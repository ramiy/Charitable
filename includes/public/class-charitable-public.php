<?php 
/**
 * Charitable Public class. 
 *
 * @package 	Charitable/Classes/Charitable_Public
 * @version     1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Public' ) ) : 

/**
 * Charitable Public class. 
 *
 * @final
 * @since 	    1.0.0
 */
final class Charitable_Public extends Charitable_Start_Object {

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {		
		$this->load_dependencies();

		$this->attach_hooks_and_filters();

		do_action( 'charitable_public_start', $this );
	}

	/**
	 * Load dependencies used for the public facing site. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function load_dependencies() {
		require_once( charitable()->get_path( 'public' ) . 'class-charitable-session.php' );
		require_once( charitable()->get_path( 'public' ) . 'class-charitable-session-donation.php' );
		require_once( charitable()->get_path( 'public' ) . 'class-charitable-template.php' );		
		require_once( charitable()->get_path( 'public' ) . 'class-charitable-template-part.php' );
		require_once( charitable()->get_path( 'public' ) . 'class-charitable-templates.php' );
	}

	/**
	 * Sets up hook and filter callback functions for public facing functionality.
	 * 
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {
		add_action('charitable_start', 		array( 'Charitable_Session', 'charitable_start' ), 1 );
		add_action('charitable_start', 		array( 'Charitable_Templates', 'charitable_start' ), 2 );
		add_action('wp_enqueue_scripts', 	array( $this, 'wp_enqueue_scripts') );
	}

	/**
	 * Loads public facing scripts and stylesheets. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function wp_enqueue_scripts() {		
		wp_register_script( 'charitable-script', charitable()->get_path( 'assets', false ) . 'js/charitable.js', array( 'jquery' ), charitable()->get_version() );
		wp_enqueue_script( 'charitable-script' );

		wp_register_style( 'charitable-styles', charitable()->get_path( 'assets', false ) . 'css/charitable.css', array(), charitable()->get_version() );
		wp_enqueue_style( 'charitable-styles' );
	}
}

endif;