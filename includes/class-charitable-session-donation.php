<?php
/**
 * The class used to set and retrieve data for donations in the current session, before they reach the database.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Session_Donation
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Session_Donation' ) ) : 

/**
 * Charitable_Session_Donation class. 
 *
 * @since		1.0.0 	
 */
class Charitable_Session_Donation {

	/**
	 * @var 	array
	 * @access 	private
	 */
	private $db_columns;

	/**
	 * Donation data.
	 *  
	 * @var 	array
	 * @access  private
	 * @since 	1.0.0
	 */
	 private $data = array();

	/**
	 * Create session donation object.  
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function __construct() {
		$db = new Charitable_Campaign_Donations_DB();
		$this->db_columns = $db->get_columns();
	}

	/**
	 * Set a value in the donation data.  
	 *
	 * @param 	string $key
	 * @param 	mixed $value
	 * @return 	mixed Session variable.
	 * @access  public
	 * @since 	1.0.0
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );

		$value = $this->sanitize_value( $key, $value );		

		if ( is_array( $value ) ) {
			$this->data[ $key ] = serialize( $value );
		} else {
			$this->data[ $key ] = $value;
		}

		return $this->data[ $key ];
	}

	/**
	 * Return a value from the donation data. 
	 *
	 * @return 	mixed
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get( $key ) {
		$key = sanitize_key( $key );
		return isset( $this->data[ $key ] ) ? maybe_unserialize( $this->data[ $key ] ) : false;
	}

	/**
	 * Sanitize a value according to the formatting required in the database schema.	
	 *
	 * @param 	string $key
	 * @param 	mixed $value
	 * @return 	mixed
	 * @access  private
	 * @since 	1.0.0
	 */	
	private function sanitize_value( $key, $value ) {
		if ( array_key_exists( $key, $this->db_columns ) ) {
			switch ( $this->db_columns[ $key ] ) {
				case '%d': 
					$value = intval( $value );
					break;

				case '%s': 
					$value = strval( $value );
					break;

				case '%f':
					$value = floatval( $value );
					break;
			}
		}

		return $value;		
	}
}

endif; // End class_exists check