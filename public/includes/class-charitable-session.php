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
 * @since 		0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Session' ) ) : 

/**
 * Charitable_Session
 *
 * @since 		0.1
 */
class Charitable_Session {

	/**
	 * Holds our session data
	 *
	 * @var 	array
	 * @access 	private
	 * @since 	0.1
	 */
	private $session;

	/**
	 * Instantiate session object. Private constructor.
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	0.1
	 */
	private function __construct( Charitable $charitable ) {
		
		$charitable->register_object( $this );

		if( ! defined( 'WP_SESSION_COOKIE' ) )
			define( 'WP_SESSION_COOKIE', 'charitable_session' );

		if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
			require_once( $charitable->get_path( 'includes' ) . 'libraries/wp-session/class-recursive-arrayaccess.php' );
		}
		
		if ( ! class_exists( 'WP_Session' ) ) {
			require_once( $charitable->get_path( 'includes' ) . 'libraries/wp-session/class-wp-session.php' );
			require_once( $charitable->get_path( 'includes' ) . 'libraries/wp-session/wp-session.php' );			
		}

		$this->session = WP_Session::get_instance();		
	}

	/**
	 * Start the session object. 
	 *
	 * @param 	Charitable $charitable
	 * @return 	Charitable_Session
	 * @access 	public
	 * @static
	 * @since 	0.1
	 */
	public static function charitable_start( Charitable $charitable ) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		return new Charitable_Session( $charitable );
	}

	/**
	 * Returns the session ID. 
	 *
	 * @return 	string Session ID
	 * @access 	public
	 * @since 	0.1
	 */
	public function get_session_id() {
		return $this->session->get_session_id();
	}
}

endif;