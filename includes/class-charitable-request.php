<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Request' ) ) :

/** 
 * Charitable Request. 
 *  
 * @class Charitable_Request
 * @version		0.1
 * @package		Charitable/Classes/Charitable_Request
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Request {

	/**
	 * @var post $post
	 * @access private
	 */
	private $post;

	/**
	 * @var Charitable_Campaign
	 * @access private
	 */
	private $campaign;

	/**
	 * @var Charitable_Donor
	 * @access private
	 */
	private $donor;

	/**
	 * @var Charitable_Donation
	 * @access private
	 */
	private $donation; 

	/**
	 * Set up the class. 
	 * 
	 * @global $post
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		global $post;

		$this->post = $post;
	}

	/** 
	 * Returns the current campaign. If there is no current campaign, return false. 
	 *
	 * @return Charitable_Campaign if we're viewing a single campaign page. False otherwise. 
	 * @access public
	 * @since 0.1
	 */
	public function get_current_campaign() {
		if ( ! isset( $this->campaign ) ) {
			if ( is_single() && get_post_type() == 'campaign' ) {
				$this->campaign = new Charitable_Campaign( $this->post );
			}
			else {
				$this->campaign = false;
			}
		}

		return $this->campaign;
	}	 
}

endif; // End class_exists check 