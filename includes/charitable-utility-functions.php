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