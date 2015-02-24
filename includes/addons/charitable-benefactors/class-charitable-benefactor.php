<?php
/**
 * The model for Benefactor relationships between Charitable campaigns and products in 3rd party extensions (EDD, WooCommerce, etc).
 *
 * @package		Charitable/Classes/Charitable_Benefactor
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Benefactor' ) ) : 

/**
 * Charitable_Benefactor
 *
 * @since 		1.0.0
 */
class Charitable_Benefactor {

	/**
	 * Core benefactor record. 
	 *
	 * @var 	Object
	 * @access  private
	 */
	private $benefactor;

	/**
	 * Create class object.
	 * 
	 * @param 	mixed 		$benefactor
	 * @access 	public
	 * @since	1.0.0
	 */
	public function __construct( $benefactor ) {
		if ( ! is_object( $benefactor ) ) {
			$benefactor = charitable()->get_db_table( 'benefactors' )->get( $benefactor );
		}

		/** @hook 	charitable_benefactor 	Use this filter to augment the $benefactor object. **/
		$this->benefactor = apply_filters( 'charitable_benefactor', $benefactor );
	}

	/**
	 * Display a short one-line summary of a benefactor (how much is contributed and from where).	
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function __toString() {
		$summary = apply_filters( 'charitable_benefactor_summary', sprintf( "%s %s.", $this->get_contribution_amount(), $this->get_contribution_type() ), $this );
		return $summary;
	}

	/**
	 * Magic getter method. 
	 *
	 * @param 	$key
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function __get( $key ) {
		return isset( $this->benefactor->$key ) ? $this->benefactor->$key : null;
	}

	/**
	 * Return the details of the benefactor (i.e. the 3rd party extension). 
	 *
	 * @return 	Object
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_benefactor() {
		return $this->benefactor;
	}

	/**
	 * Return the contribution as a nicely formatted amount. 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_contribution_amount() {
		if ( $this->benefactor->contribution_amount_is_percentage ) {
			$amount = apply_filters( 'charitable_benefactor_contribution_amount_percentage', $this->benefactor->contribution_amount . '%', $this->benefactor->contribution_amount, $this );
		}
		else {
			$amount = apply_filters( 'charitable_benefactor_contribution_amount_fixed', charitable()->get_currency_helper()->get_monetary_amount( $this->benefactor->contribution_amount ), $this->benefactor->contribution_amount, $this );
		}

		return $amount;
	}

	/**
	 * Return the type of the contribution. Either per purchase or per  
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_contribution_type() {
		$type = $this->benefactor->contribution_amount_is_per_item ? __( 'per item', 'charitable' ) : __( 'per purchase', 'charitable' );
		return apply_filters( 'charitable_benefactor_contribution_type', $type, $this->benefactor->contribution_amount_is_per_item, $this );
	}
}

endif; // End class_exists check