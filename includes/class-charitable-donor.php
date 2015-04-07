<?php
/**
 * The class that models Donors in Charitable. 
 *
 * This extends the Charitable_User class, which itself is a 
 * child of WP_User. Charitable_User handles the 
 *
 * @package		Charitable/Classes/Charitable_Donor
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Donor' ) ) : 

/**
 * Charitable_Donor is a sub-class of Charitable_User.
 *
 * @see 		Charitable_User
 * @since 		1.0.0
 */
class Charitable_Donor extends Charitable_User {

}

endif; // End class_exists check