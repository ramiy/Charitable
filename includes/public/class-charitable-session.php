<?php
/**
 * Charitable Session class.
 * 
 * The responsibility of this class is to manager the user sessions.
 *
 * @package		Charitable
 * @subpackage	Charitable/Charitable Session
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 		1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Session' ) ) : 

/**
 * Charitable_Session
 *
 * @since 		1.0.0
 */
class Charitable_Session extends Charitable_Start_Object {

	/**
	 * Holds our session data
	 *
	 * @var 	array
	 * @access 	private
	 * @since 	1.0.0
	 */
	private $session;

	/**
	 * Instantiate session object. Private constructor.
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {	
		if ( ! defined( 'WP_SESSION_COOKIE' ) )
			define( 'WP_SESSION_COOKIE', 'charitable_session' );

		if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
			require_once( charitable()->get_path( 'includes' ) . 'libraries/wp-session/class-recursive-arrayaccess.php' );
		}
		
		if ( ! class_exists( 'WP_Session' ) ) {
			require_once( charitable()->get_path( 'includes' ) . 'libraries/wp-session/class-wp-session.php' );
			require_once( charitable()->get_path( 'includes' ) . 'libraries/wp-session/wp-session.php' );			
		}

		/* Set the expiration length & variant of the session */
		add_filter( 'wp_session_expiration', array( $this, 'set_session_length' ), 99999 );
		add_filter( 'wp_session_expiration_variant', array( $this, 'set_session_expiration_variant_length' ), 99999 );		

		$this->session = WP_Session::get_instance();			
	}

	/**
	 * Returns the session ID. 
	 *
	 * @return 	string Session ID
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_session_id() {
		return $this->session->session_id;
	}

	/**
	 * Return a session variable. 
	 *
	 * @param 	string $key
	 * @return 	mixed Session variable
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get( $key ) {
		$key = sanitize_key( $key );
		return isset( $this->session[ $key ] ) ? maybe_unserialize( $this->session[ $key ] ) : false;
	}

	/**
	 * Set a session variable.  
	 *
	 * @param 	string $key
	 * @param 	mixed $value
	 * @return 	mixed The session variable value. 
	 * @access  public
	 * @since 	1.0.0
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );

		if ( is_array( $value ) ) {
			$this->session[ $key ] = serialize( $value );
		} else {
			$this->session[ $key ] = $value;
		}

		return $this->session[ $key ];
	}

	/**
	 * Set the length of the cookie session to 24 hours. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function set_session_length() {
		return ( 30 * 60 * 24 );
	}

	/**
	 * Set the cookie expiration variant time to 23 hours. 
	 *	
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function set_session_expiration_variant_length() {
		return ( 30 * 60 * 23 );
	}
}

endif;