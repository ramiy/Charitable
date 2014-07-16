<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! interface_exists( 'Charitable_Donation_Form_Interface' ) ) : 

/**
 * Donation form interface. 
 *
 * This defines a strict interface that donation forms must implement.
 *
 * @interface Charitable_Donation_Form_Interface
 * @version		0.0.1
 * @package		Charitable/Interfaces/Charitable_Donation_Form_Interface
 * @category	Interface
 * @author 		Studio164a
 */
interface Charitable_Donation_Form_Interface {

	/**
	 * Render the donation form. 
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function render();

	/**
	 * Save the submitted donation.
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function save_donation();
}

endif; // End interface_exists check.