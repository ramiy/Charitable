<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Donation_Form' ) ) : 

/**
 * Donation_Form model
 *
 * @class 		Charitable_Donation_Form
 * @version		0.0.1
 * @package		Charitable/Classes/Donation_Form
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Donation_Form implements Charitable_Donation_Form_Interface {

	/** 
	 * @var Charitable_Campaign
	 */
	private $campaign;

	/**
	 * @var array
	 */
	private $form_fields;

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
		$this->form_fields = $this->campaign->get('campaign_donation_form');
	}

	/**
	 * Render the donation form. 
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function render() {
		new Charitable_Template( 'campaign/donation-form' );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function save_donation() {

	}
}

endif; // End class_exists check