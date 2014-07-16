<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Donation' ) ) : 

/**
 * Donation model
 *
 * @class 		Charitable_Donation
 * @version		0.0.1
 * @package		Charitable/Classes/Donation
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Donation {
	
	/**
	 * @var Charitable_Gateway_Interface The payment gateway used to process the donation.
	 */
	private $gateway;

	/**
	 * @var array The campaign that was donated to.
	 */
	private $campaign;

	/**
	 * @var WP_Post The WP_Post object associated with this donation.
	 */
	private $post;

	/**
	 * @var WP_User The WP_user object of the person who donated.
	 */
	private $user;

	/**
	 * Class constructor. 
	 * 
	 * @param $post The post ID or WP_Post object for this this donation.
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function __construct($post) {
		if ( ! is_a( 'WP_Post', $post ) ) {
			$post = get_post( $post );
		}

		$this->post = $post;
	}

	/**
	 * Returns the campaign's post_meta values. 
	 *
	 * @see get_post_meta
	 * 
	 * @param string $meta_name The meta name to search for.
	 * @param bool $single Whether to return a single value or an array. 
	 * @return mixed This will return an array if single is false. If it's true, 
	 *  	the value of the meta_value field will be returned.
	 * @access public
	 * @since 0.0.1
	 */
	public function get( $meta_name, $single = true ) {
		return get_post_meta( $this->post->ID, $meta_name, $single );
	}

	/**
	 * Returns the name of the gateway used to process the donation.
	 *
	 * @return string The name of the gateway.
	 * @access public
	 * @since 0.0.1
	 */
	public function get_gateway() {
		if ( ! isset( $this->gateway ) ) {
			$this->gateway = $this->get( 'donation_gateway' );
		}

		return $this->gateway;
	}

	/** 
	 * The status of the payment. 
	 *
	 * 
	 */ 
}

endif; // End class_exists check