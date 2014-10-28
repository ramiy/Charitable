<?php 

/**
 * Charitable Core Functions. 
 *
 * General core functions.
 *
 * @author 		Studio164a
 * @category 	Core
 * @package 	Charitable/Functions
 * @version     0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_charitable' ) ) : 
/**
 * This returns the original Charitable object (created just above). 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return 	Charitable
 * @since 	0.1.0
 */
function get_charitable() {
    return Charitable::get_instance();
}
endif;

if ( ! function_exists('charitable_get_template') ) : 
/**
 * Displays a template. 
 *
 * @since 	0.1.0
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
 * @since 	0.1.0
 */
function charitable_get_helper( $class_key ) {
	$class_name = 'Charitable_' . ucfirst( $class_key );
	
	if ( ! class_exists( $class_name ) ) {
		return false;
	}

	return get_charitable()->get_registered_object( $class_name );
}
endif;