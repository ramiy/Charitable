<?php
/**
 * Charitable Install class.
 * 
 * The responsibility of this class is to manage the events that need to happen 
 * when the plugin is activated.
 *
 * @package		Charitable
 * @subpackage	Charitable/Charitable Install
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 		0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Install' ) ) : 

/**
 * Charitable_Install
 *
 * @since 		0.1
 */
class Charitable_Install {

	/**
	 * Install the plugin. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	0.1
	 */
	private function __construct() {		
		$this->create_tables();
	}

	/**
	 * Static method to instantiate the class.
	 *
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	0.1
	 */
	public static function install() {
		new Charitable_Install();
	}

	/**
	 * Create database tables. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	0.1
	 */
	private function create_tables() {
		get_charitable()->get_db_table('donations')->create_table();
	}
}

endif;