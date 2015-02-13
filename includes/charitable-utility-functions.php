<?php 

/**
 * Charitable Utility Functions. 
 *
 * Utility functions.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Orders an array by the priority key.
 *
 * @param 	array $a
 * @param 	array $b
 * @return 	int
 * @since 	1.0.0
 */
function charitable_priority_sort($a, $b) {
	if ( $a['priority'] == $b['priority'] ) {
		return 0;
	}

	return $a['priority'] < $b['priority'] ? -1 : 1;
}