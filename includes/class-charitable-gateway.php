<?php
/**
 * Class that sets up the gateways. 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Gateway
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Gateway' ) ) : 

/**
 * Charitable_Gateway
 *
 * @since 		1.0.0
 */
class Charitable_Gateway extends Charitable_Start_Object {

	/**
	 * All available payment gateways. 
	 *
	 * @var 	array
	 * @access  private
	 */
	private $gateways;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {
		$this->attach_hooks_and_filters();		

		/**
		 * To register a new gateway, you need to hook into this filter and 
		 * give Charitable the name of your gateway class.
		 */
		$this->gateways = apply_filters( 'charitable_payment_gateways', array(
			'offline' 	=> 'Charitable_Gateway_Offline', 
			'paypal'	=> 'Charitable_Gateway_Paypal'
		) );
	}

	/**
	 * Attach callbacks to hooks and filters.  
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {
		add_action( 'charitable_after_save_donation', array( $this, 'send_donation_to_gateway' ), 10, 2 );
		add_action( 'charitable_enable_gateway', array( $this, 'handle_gateway_request' ) );
		add_action( 'charitable_disable_gateway', array( $this, 'handle_gateway_request' ) );

		do_action( 'charitable_gateway_start', $this );		
	}

	/**
	 * Receives a request to enable or disable a payment gateway and validates it before passing it off.
	 * 
	 * @param 	array
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function handle_gateway_request() {
		if ( ! wp_verify_nonce( $_REQUEST[ '_nonce' ], 'gateway' ) ) {
			wp_die( __( 'Cheatin\' eh?!', 'charitable' ) );
		}

		$gateway = isset( $_REQUEST[ 'gateway_id' ] ) ? $_REQUEST[ 'gateway_id' ] : false;

		/* Gateway must be set */
		if ( false === $gateway ) {
			wp_die( __( 'Missing gateway.', 'charitable' ) );
		}		

		/* Validate gateway. */
		if ( ! isset( $this->gateways[ $gateway ] ) ) {
			wp_die( __( 'Invalid gateway.', 'charitable' ) );
		}

		/* All good, so disable or enable the gateway */
		if ( 'charitable_disable_gateway' == current_filter() ) {
			$this->disable_gateway( $gateway );
		}
		else {
			$this->enable_gateway( $gateway );
		}	
	}

	/**
	 * Enable a payment gateway. 
	 *
	 * @return  void
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function enable_gateway( $gateway ) {
		$settings = get_option( 'charitable_settings' );

		$active_gateways = isset( $settings[ 'active_gateways' ] ) ? $settings[ 'active_gateways' ] : array();
		$active_gateways[ $gateway ] = $this->gateways[ $gateway ];
		$settings[ 'active_gateways' ] = $active_gateways;

		update_option( 'charitable_settings', $settings );

		do_action( 'charitable_gateway_enable', $gateway );
	}

	/**
	 * Disable a payment gateway. 
	 *
	 * @return  void
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function disable_gateway( $gateway ) {
		$settings = get_option( 'charitable_settings' );

		if ( ! isset( $settings[ 'active_gateways' ][ $gateway ] ) ) {
			return;
		}
		
		unset( $settings[ 'active_gateways' ][ $gateway ] );

		update_option( 'charitable_settings', $settings );		

		do_action( 'charitable_gateway_disable', $gateway );
	}	

	/**
	 * Send the donation/donor off to the gateway.  
	 *
	 * @param 	Charitable_Campaign 	$campaign
	 * @param 	int 					$donation_id
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function send_donation_to_gateway( $campaign, $donation_id ) {
		
	}

	/**
	 * Returns all available payment gateways. 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_available_gateways() {
		return $this->gateways;
	}

	/**
	 * Returns the current active gateways. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_active_gateways() {
		return charitable_get_option( 'active_gateways' ) ? charitable_get_option( 'active_gateways' ) : array();
	}

	/**
	 * Return the gateway class name for a given gateway.	 
	 *
	 * @param 	string 	$gateway
	 * @return  string|false 	
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_gateway( $gateway ) {
		return isset( $this->gateways[ $gateway ] ) ? $this->gateways[ $gateway ] : false;
	}

	/**
	 * Returns whether the passed gateway is active. 
	 *
	 * @param 	string 		$gateway_id
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function is_active_gateway( $gateway_id ) {		
		return array_key_exists( $gateway_id, $this->get_active_gateways() );
	}

	/**
	 * Returns the default gateway. 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_default_gateway() {
	
	}
}

endif; // End class_exists check