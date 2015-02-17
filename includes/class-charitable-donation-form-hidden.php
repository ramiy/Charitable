<?php
/**
 * Donation Form Hidden model
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Donation_Form_Hidden
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Form_Hidden' ) ) : 

/**
 * Donation Form Hidden model
 *
 * @since		1.0.0
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
	 * @since 1.0.0
	 */
	public function __construct(Charitable_Campaign $campaign) {
		$this->campaign = $campaign;
	}

	/**
	 * Render nothing. 
	 *
	 * @return void
	 * @access public
	 * @since 1.0.0
	 */
	public function render() {
		charitable_get_template( 'campaign/donation-form-hidden' );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return void
	 * @access public
	 * @since 1.0.0
	 */
	public function save_donation() {
		/**
		 * Do something here, or save it elsewhere?
		 */
	}
}

endif; // End class_exists check