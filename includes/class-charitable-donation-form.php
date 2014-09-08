<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Donation_Form' ) ) : 

/**
 * Donation_Form model
 *
 * @class 		Charitable_Donation_Form
 * @version		0.1
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
	 * @since 0.1
	 */
	public function __construct(Charitable_Campaign $campaign) {
		$this->campaign = $campaign;
	}

	/**
	 * Returns the fields to be displayed in the donation form. 
	 *
	 * @uses charitable_donation_form_fields
	 * 
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function get_fields() {
		return apply_filters( 'charitable_donation_form_fields', 
			array(
				'first_name' => array( 
					'label' 	=> __( 'First name', 'charitable' ), 
					'meta_key' 	=> '_donor_first_name', 
					'type'		=> 'text'
				),
				'last_name' => array( 
					'label' 	=> __( 'Last name', 'charitable' ), 
					'meta_key' 	=> '_donor_last_name',
					'type'		=> 'text'
				),
				'address' => array( 
					'label' 	=> __( 'Address', 'charitable' ), 
					'meta_key' 	=> '_donor_address',
					'type'		=> 'text'
				),
				'address_2' => array( 
					'label' 	=> __( 'Address 2', 'charitable' ), 
					'meta_key' 	=> '_donor_address_2',
					'type'		=> 'text' 
				),
				'city' => array( 
					'label' 	=> __( 'City', 'charitable' ), 
					'meta_key' 	=> '_donor_city',
					'type'		=> 'text' 
				),
				'state' => array( 
					'label' 	=> __( 'State', 'charitable' ), 
					'meta_key' 	=> '_donor_state',
					'type'		=> 'text' 
				),
				'postcode' => array( 
					'label' 	=> __( 'Postcode / ZIP code', 'charitable' ), 
					'meta_key' 	=> '_donor_postcode',
					'type'		=> 'text'
				),
				'country' => array( 
					'label' 	=> __( 'Country', 'charitable' ), 
					'meta_key' 	=> '_donor_country',
					'type'		=> 'select', 
					'options' 	=> $this->charitable->get_location_helper()->get_countries()
				),
				'phone' => array( 
					'label' 	=> __( 'Phone', 'charitable' ), 
					'meta_key' 	=> '_donor_phone',
					'type'		=> 'text' 
				),
				'email' => array( 
					'label' 	=> __( 'Email', 'charitable' ), 
					'meta_key' 	=> '_donor_email',
					'type'		=> 'email',
					'required_in_form' => true
				),
				'comment' => array( 
					'label' 	=> __( 'Comment', 'charitable' ), 
					'meta_key' 	=> '_donor_comment',
					'type'		=> 'textarea'
				)
			) 
		);
	}

	/**
	 * Render the donation form. 
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function render() {
		new Charitable_Template( 'campaign/donation-form' );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function save_donation() {

	}
}

endif; // End class_exists check