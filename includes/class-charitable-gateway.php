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
class Charitable_Gateway {

	/**
	 * @var 	Charitable 	$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * All available payment gateways. 
	 *
	 * @var 	array
	 * @access  private
	 */
	private $gateways;

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @see 	charitable_start hook
	 * 
	 * @param 	Charitable 	$charitable 
	 * @return 	void
	 * @static 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function charitable_start( Charitable $charitable ) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Gateway( $charitable );
	}

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param 	Charitable 	$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;	

		$this->attach_hooks_and_filters();

		$this->include_default_gateways();

		/**
		 * To register a new gateway, you need to hook into this filter and 
		 * give Charitable the name of your gateway class.
		 */
		$this->gateways = apply_filters( 'charitable_payment_gateways', array(
			'Charitable_Gateway_Offline', 
			'Charitable_Gateway_Paypal'
		) );

		$this->registered_gateways = apply_filters( 'charitable_registered_gateways', array() );

		/**
		 * The main Charitable class will save the one instance of this object.
		 */
		$this->charitable->register_object( $this );
	}

	/**
	 * Attach callbacks to hooks and filters.  
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {
		add_action( 'charitable_after_save_donation', 	array( $this, 'send_donation_to_gateway' ), 10, 2 );

		do_action( 'charitable_gateway_start', $this );		
	}

	/**
	 * Include default gateways provided in core. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function include_default_gateways() {
		include_once( $this->charitable->get_path( 'includes' ) . 'gateways/abstract-class-charitable-gateway.php' );
		include_once( $this->charitable->get_path( 'includes' ) . 'gateways/class-charitable-gateway-offline.php' );
		include_once( $this->charitable->get_path( 'includes' ) . 'gateways/class-charitable-gateway-paypal.php' );
	}

	/**
	 * Register default gateways. 
	 * 
	 * @param 	array
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_default_gateways( $gateways ) {
		
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