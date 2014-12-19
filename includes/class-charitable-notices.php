<?php
/**
 * Contains the class that is used to register and retrieve notices like errors, warnings, success messages, etc.
 *
 * @class 		Charitable_Notices
 * @version		1.0
 * @package		Charitable/Classes/Charitable_Notices
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @category	Class
 * @author 		Studio164a
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Notices' ) ) : 

/**
 * Charitable_Notices
 *
 * @since 		1.0.0
 */
class Charitable_Notices {

	/**
	 * The single instance of this class.  
	 *
	 * @var 	Charitable_Notices|null
	 * @access  private
	 * @static
	 */
	private static $instance = null;

	/**
	 * The array of notices.  
	 *
	 * @var 	array
	 * @access  protected
	 */
	protected $notices = array();

	/**
	 * Returns and/or create the single instance of this class.  
	 *
	 * @return 	Charitable_Notices
	 * @access  public
	 * @since 	1.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Charitable_Notices();
		}

		return self::$instance;
	}

	/**
	 * Create class object. A private constructor, so this is used in a singleton context. 
	 * 
	 * @return 	void
	 * @access 	private
	 * @since	1.0.0
	 */
	private function __construct() {
	}

	/**
	 * Adds a notice message. 
	 *
	 * @param 	string 		$message
	 * @param 	string 		$type
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_notice( $message, $type ) {
		$this->notices[$type][] = $message; 
	}

	/**
	 * Adds an error message. 
	 *
	 * @param 	string 		$message
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_error( $message ) {
		$this->add_notice( $message, 'error' );
	}
}	

endif; // End class_exists check