<?php
/**
 * Charitable Uninstall class.
 * 
 * The responsibility of this class is to manage the events that need to happen 
 * when the plugin is deactivated.
 *
 * @package		Charitable
 * @subpackage	Charitable/Charitable Upgrade
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 		0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Uninstall' ) ) : 

/**
 * Charitable_Uninstall
 * 
 * @since 		0.1
 */
class Charitable_Uninstall {

	/**
	 * @var 	Charitable
	 * @access 	private 
	 */
	private $charitable;

	/**
	 * Uninstall the plugin.
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	0.1
	 */
	private function __construct( Charitable $charitable ){
		$this->charitable = $charitable;

		$this->remove_caps();
	}

	/**
	 * Uninstall the plugin.
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	0.1
	 */
	public static function uninstall( Charitable $charitable ) {
		/** 
		 * This prevents the class being instantiated at 
		 * any time other than deactivation.
		 */
		if ( ! $charitable->is_deactivation() ) {
			return;
		}

		new Charitable_Uninstall( $charitable );
	}

	/**
	 * Remove plugin-specific roles
	 *
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	0.1
	 */
	public static function remove_caps() {
		$roles = new Charitable_Roles();
		$roles->remove_caps();
	}
}

endif;