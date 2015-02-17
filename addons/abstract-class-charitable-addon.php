<?php
/**
 * Main class for setting up the Charitable Benefactors Addon, which is programatically activated by child themes.
 *
 * @package		Charitable/Classes/Charitable_Benefactors_Addon
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Benefactors_Addon' ) ) : 

/**
 * Charitable_Benefactors_Addon
 *
 * @abstract
 * @since 		1.0.0
 */
abstract class Abstract_Charitable_Addon {

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

		/* Update the addons array in the database to ensure this is loaded every time */
		$active_addons = get_option( 'charitable_active_addons', array() );

		if ( ! in_array( __CLASS__, $active_addons ) ) {
			$active_addons[] = __CLASS__;
		}

		update_option( 'charitable_active_addons', $active_addons );

		/* Load the addon */
		$this->load();
	}

	/**
	 * Load the addon. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	abstract public static function load();
}

endif; // End class_exists check