<?php 
/**
 * Charitable Utility Functions. 
 *
 * Utility functions.
 *
 * @package 	Charitable/Functions/Utility
 * @version     1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Orders an array by the priority key.
 *
 * @param 	array 	$a
 * @param 	array 	$b
 * @return 	int
 * @since 	1.0.0
 */
function charitable_priority_sort($a, $b) {
	if ( $a['priority'] == $b['priority'] ) {
		return 0;
	}

	return $a['priority'] < $b['priority'] ? -1 : 1;
}

/**
 * Checks whether function is disabled.
 *
 * Full credit to Pippin Williamson and the EDD team. 
 *
 * @param 	string  $function 	Name of the function.
 * @return 	bool 				Whether or not function is disabled.
 * @since 	1.0.0
 */
function charitable_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );

	return in_array( $function, $disabled );
}

/**
 * Returns the current URL. 
 *
 * @see 	https://kovshenin.com/2012/current-url-in-wordpress/
 * @global 	WP 		$wp
 * @return  string
 * @since   1.0.0
 */
function charitable_get_current_url() {
	global $wp;
    return esc_url_raw( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
}