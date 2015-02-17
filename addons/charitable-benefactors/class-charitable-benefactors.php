<?php
/**
 * Main class for setting up the Charitable Benefactors Addon, which is programatically activated by child themes.
 *
 * @package		Charitable/Classes/Charitable_Benefactors
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Benefactors' ) ) : 

/**
 * Charitable_Benefactors
 *
 * @since 		1.0.0
 */
class Charitable_Benefactors extends Abstract_Charitable_Addon {

	/**
	 * Activate the addon. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function activate() {		
		parent::activate();

		new Charitable_Benefactors();

		$table = new Charitable_Benefactors_DB();
		@$table->create_table();
	}

	/**
	 * Load required files. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function load() {
		require_once( 'class-charitable-benefactors-db.php' );
	}
}

endif; // End class_exists check