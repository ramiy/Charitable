<?php 

/**
 * Charitable Core Admin Functions
 *
 * General core functions available only within the admin area.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Admin/Functions
 * @version     0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'charitable_admin_view' ) ) : 

/**
 * Load a view from the admin/views folder. 
 * 
 * If the view is not found, an Exception will be thrown.
 *
 * Example usage: charitable_admin_view('metaboxes/cause-metabox');
 *
 * @return void
 * @since 0.0.1
 */
function charitable_admin_view($view) {
	$filename = get_charitable()->get_admin_path() . 'views/' . $view . '.php';

	if ( ! is_readable( $filename ) ) {
		echo '<pre>';
		throw new Exception( sprintf( '<strong>%s</strong>: %s (%s)', 
			__( 'Error', 'charitable' ), 
			__( 'View not found or is not readable.', 'charitable' ), 
			$filename
		) );
		echo '</pre>';
	}

	include_once( $filename );
}

endif; // End function_exists check

if ( ! function_exists('charitable_priority_sort') ) :

/**
 * Orders an array by the priority key.
 *
 * @param array $a
 * @param array $b
 * @return int
 * @since 0.0.1
 */
function charitable_priority_sort($a, $b) {
	if ( $a['priority'] == $b['priority'] ) {
		return 0;
	}

	return $a['priority'] < $b['priority'] ? -1 : 1;
}

endif; // End function_exists check