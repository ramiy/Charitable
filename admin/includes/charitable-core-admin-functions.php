<?php 

/**
 * Charitable Core Admin Functions
 *
 * General core functions available only within the admin area.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Admin/Functions
 * @version     1.0.0
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
	$filename = charitable()->get_path( 'admin' ) . 'views/' . $view . '.php';

	if ( ! is_readable( $filename ) ) {
		_doing_it_wrong( 'charitable_admin_view', __( 'Passed view (' . $filename . ') not found or is not readable.', 'charitable' ), '1.0.0' );
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
	return charitable_get_helper( 'Admin_Settings' );
}