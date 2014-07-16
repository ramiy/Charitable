<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Donation_Form_Hidden' ) ) : 

/**
 * Donation Form Hidden model
 *
 * @class 		Charitable_Donation_Form_Hidden
 * @version		0.0.1
 * @package		Charitable/Classes/Charitable_Donation_Form_Hidden
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Donation_Form_Hidden implements Charitable_Donation_Form_Interface {

	/** 
	 * @var Charitable_Campaign
	 */
	private $campaign;

	/**
	 * Create a donation form object.
	 *
	 * @param Charitable_Campaign $campaign
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function __construct(Charitable_Campaign $campaign) {
		$this->campaign = $campaign;
	}

	/**
	 * Render nothing. 
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function render() {
		charitable_get_template( 'campaign/donation-form-hidden' );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function save_donation() {
		/**
		 * Do something here, or save it elsewhere?
		 */
	}
}

endif; // End class_exists check