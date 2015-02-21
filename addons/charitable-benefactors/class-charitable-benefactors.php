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
class Charitable_Benefactors implements Charitable_Addon_Interface {

	/**
	 * Activate the addon. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function activate() {		
		
		/* This method should only be called on the charitable_activate_addon hook */
		if ( 'charitable_activate_addon' !== current_filter() ) {
			return false;
		}

		new Charitable_Benefactors();

		self::load();

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

		add_filter( 'charitable_db_tables', array( 'Charitable_Benefactors', 'register_table' ) );
		add_action( 'charitable_uninstall', array( 'Charitable_Benefactors', 'uninstall' ) );
	}

	/**
	 * Register table. 
	 *
	 * @param 	array 		$tables
	 * @return 	array
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function register_table( $tables ) {
		$tables['benefactors'] = 'Charitable_Benefactors_DB';
		return $tables;
	}

	/**
	 * Called when Charitable is uninstalled and data removal is set to true.  
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function uninstall() {
		if ( 'charitable_uninstall' != current_filter() ) {
			return;
		}
		
		global $wpdb;		

		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "charitable_benefactors" );

		delete_option( $wpdb->prefix . 'charitable_benefactors_db_version' );
	}
}

endif; // End class_exists check