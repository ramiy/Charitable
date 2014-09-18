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
	 * @access 	public
	 * @since 	0.1
	 */
	public function __construct() {		
		$this->create_tables();
	}

	/**
	 * Create wp roles and assign capabilities
	 *
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	0.1
	 */
	private function setup_roles(){
		new Charitable_Roles(Charitable::get_instance());
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