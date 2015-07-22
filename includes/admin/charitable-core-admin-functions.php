<?php 

/**
 * Charitable Core Admin Functions
 *
 * General core functions available only within the admin area.
 * 
 * @package 	Charitable/Functions/Admin
 * @version     1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load a view from the admin/views folder. 
 * 
 * If the view is not found, an Exception will be thrown.
 *
 * Example usage: charitable_admin_view('metaboxes/cause-metabox');
 *
 * @param 	string 		$view 			The view to display. 
 * @param 	array 		$view_args 		Optional. Arguments to pass through to the view itself
 * @return 	void
 * @since 	1.0.0
 */
function charitable_admin_view( $view, $view_args = array() ) {
	$filename = apply_filters( 'charitable_admin_view_path', charitable()->get_path( 'admin' ) . 'views/' . $view . '.php', $view, $view_args );

	if ( ! is_readable( $filename ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Passed view (' . $filename . ') not found or is not readable.', 'charitable' ), '1.0.0' );
	}

	ob_start();

	include( $filename );

	ob_end_flush();
}

/**
 * Returns the Charitable_Admin_Settings helper.
 *
 * @return 	Charitable_Admin_Settings
 * @since 	1.0.0
 */
function charitable_get_admin_settings() {
	return charitable_get_helper( 'settings' );
}