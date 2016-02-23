<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Donation Factory Class
 *
 * The Charitable donation factory creating the right donation objects.
 *
 * @class 		Charitable_Donation_Factory
 * @version		1.3.0
 * @since		1.3.0
 * @package		Charitable/Classes
 * @category	Class
 * @author 		Eric Daams
 */
class Charitable_Donation_Factory {

	/**
	 * Get donation.
	 *
	 * @param bool $the_donation (default: false)
	 * @return WC_Order|bool
	 */
	public function get_donation( $the_donation = false ) {
		global $post;

		if ( false === $the_donation ) {
			$the_donation = $post;
		} elseif ( is_numeric( $the_donation ) ) {
			$the_donation = get_post( $the_donation );
		} elseif ( $the_donation instanceof WC_Order ) {
			$the_donation = get_post( $the_donation->id );
		}

		if ( ! $the_donation || ! is_object( $the_donation ) ) {
			return false;
		}

		$donation_id  = absint( $the_donation->ID );
		$post_type = $the_donation->post_type;

		$classname = $this->get_donation_class( $the_donation );

		if ( ! class_exists( $classname ) ) {
			$classname = 'Charitable_Donation';
		}

		return new $classname( $the_donation );
	}


	/**
	 * Create a class name e.g. Charitable_Donation_Type_Class instead of WC_donation_type-class.
	 * @param  string $donation_type
	 * @return string|false
	 */
	private function get_classname_from_donation_type( $donation_type ) {
		return 'Charitable_' . implode( '_', array_map( 'ucfirst', explode( '-', $donation_type ) ) );
	}

	/**
	 * Get the product class name.
	 * @param  WP_Post $the_donation
	 * @return string
	 */
	private function get_donation_class( $the_donation ) {
		$donation_id = absint( $the_donation->ID );
		$donation_type  = $the_donation->post_type;

		$classname = $this->get_classname_from_donation_type( $donation_type );

		// Filter classname so that the class can be overridden if extended.
		return apply_filters( 'charitable_donation_class', $classname, $donation_type, $donation_id );
	}

}
