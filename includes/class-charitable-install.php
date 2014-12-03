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
 * @since 		1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Install' ) ) : 

/**
 * Charitable_Install
 *
 * @since 		1.0.0
 */
class Charitable_Install {

	/**
	 * @var 	Charitable
	 * @access 	private 
	 */
	private $charitable;

	/**
	 * Install the plugin. 
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;

		$this->setup_roles();
		$this->create_tables();		
	}

	/**
	 * Create wp roles and assign capabilities
	 *
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	1.0.0
	 */
	private function setup_roles(){
		$roles = new Charitable_Roles();
		$roles->add_roles();
		$roles->add_caps();
	}

	/**
	 * Create database tables. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function create_tables() {
		@$this->charitable->get_db_table( 'donations' )->create_table();
	}
}

endif;