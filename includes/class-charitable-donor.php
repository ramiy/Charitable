<?php
/**
 * Contains the class that models a Donor in Charitable.
 *
 * @class 		Charitable_Donor
 * @version		1.0
 * @package		Charitable/Classes/Charitable_Donor
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @category	Class
 * @author 		Studio164a
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Donor' ) ) : 

/**
 * Charitable_Donor
 *
 * @since 		1.0.0
 */
class Charitable_Donor {

	/**
	 * WP_User object. 
	 *
	 * @var 	WP_User
	 * @access  private
	 */
 	private $user;

	/**
	 * Create class object.
	 * 
	 * @param 	int 	$user_id
	 * @return 	void
	 * @access 	public
	 * @since	1.0.0
	 */
	public function __construct( $user_id ) {
		$this->user = new WP_User( $user_id );
	}

	/**
	 * Create a new donor. 
	 *
	 * @param 	array 			$submitted
	 * @return 	int|false
	 * @static
	 * @access  public
	 * @since 	1.0.0
	 */
	public static function create( $submitted ) {
		$user_data = array( 'role' => 'donor' );

		/**
		 * Set the user's email address.
		 */
		if ( isset( $submitted['user_email'] ) ) {
			$user_data['user_email'] = $submitted['user_email'];
			unset( $submitted['user_email'] );
		}
		elseif ( isset( $submitted['email'] ) ) {
			$user_data['user_email'] = $submitted['email'];
			unset( $submitted['email'] );
		}
		else {
			/**
			 * @todo 	Set error message. 
			 */
			return false;
		}

		/**
		 * Set their password, if provided. 
		 */
		if ( isset( $submitted['password'] ) ) {
			$user_data['user_pass'] = $submitted['password'];
			unset( $user_data['password'] );
		}
		else {
			$user_data['user_pass'] = NULL;
		}

		/**
		 * Set their username, if provided. Otherwise it's set to their email address.
		 */
		if ( isset( $submitted['username'] ) ) {
			$user_data['user_login'] = $submitted['username'];
			unset( $user_data['username'] );		
		}
		else {
			$user_data['user_login'] = $user_data['user_email'];
		}

		/**
		 * Set their first name and last name, if provided.
		 */
		if ( isset( $submitted['first_name'] ) ) {
			$user_data['first_name'] = $submitted['first_name'];
			unset( $submitted['first_name'] );
		}

		if ( isset( $submitted['last_name'] ) ) {
			$user_data['last_name'] = $submitted['last_name'];
			unset( $submitted['last_name'] );
		}

		$user_id = wp_insert_user( $user_data );

		if ( is_wp_error( $user_id ) ) {

		}

		/**
		 * Finally, loop over all the other provided values and save them as user meta fields. 
		 */
		foreach ( $submitted as $key => $value ) {
			update_user_meta( $user_id, 'donor_' . $key, $value );
		}

		return $user_id;
	}
}

endif; // End class_exists check