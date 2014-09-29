<?php 

/**
 * Charitable Core Functions
 *
 * General core functions.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Functions
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists('charitable_get_template') ) : 
/**
 * Displays a template. 
 *
 * @since 0.1
 */
function charitable_get_template() {
	
}

endif; // End function_exists check

if ( ! function_exists( 'charitable_get_helper' ) ) : 
/**
 * Returns a helper class. 
 *
 * @param 	string $class_key
 * @return 	mixed
 * @since 	0.1
 */
function charitable_get_helper( $class_key ) {
	$class_name = 'Charitable_' . ucfirst( $class_key );
	
	if ( ! class_exists( $class_name ) ) {
		return false;
	}

	return get_charitable()->get_registered_object( $class_name );
}

endif;