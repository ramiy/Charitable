<?php
/**
 * Class that manages the display and processing of the [charitable_profile] shortcode.
 *
 * @package		Charitable/Classes/Charitable_Profile_Shortcode
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Profile_Shortcode' ) ) : 

/**
 * Charitable_Profile_Shortcode
 *
 * @since 		1.0.0
 */
class Charitable_Profile_Shortcode extends Charitable_Form {

	/**
	 * Shortcode parameters. 
	 *
	 * @var 	array
	 * @access  protected
	 */
	protected $shortcode_args;

	/**
	 * @var 	string
	 */
	protected $nonce_action = 'charitable_user_profile';

	/**
	 * @var 	string
	 */
	protected $nonce_name = '_charitable_user_profile_nonce';

	/**
	 * Action to be executed upon form submission. 
	 *
	 * @var 	string
	 * @access  protected
	 */
	protected $form_action = 'update-profile';

	/**
	 * Create class object.
	 * 
	 * @param 	array 		$atts 		User-defined shortcode attributes.
	 * @access 	public
	 * @since	1.0.0
	 */
	public function __construct( $atts ) {	
		$this->id = uniqid();	
		$this->shortcode_args = shortcode_atts( array(), $atts, 'charitable_profile' );	    

		$this->attach_hooks_and_filters();	
	}

	/**
	 * The shortcode's callback method. 
	 *
	 * This receives the user-defined attributes and passes the logic off to the class. 
	 *
	 * @param 	array 		$atts 		User-defined shortcode attributes.
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function shortcode( $atts ) {		

		if ( ! is_user_logged_in() ) {
			return wp_login_form( apply_filters( 'charitable_profile_shortcode_login_args', array() ) );
		}

		ob_start();

		$template = charitable_template( 'shortcodes/profile.php', false );
		$template->set_view_args( array( 
			'form' => new Charitable_Profile_Shortcode( $atts ) 
		) );
		$template->render();

		return apply_filters( 'charitable_profile_shortcode', ob_get_clean() );
	}

	/**
	 * Profile fields to be displayed.  	
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_fields() {
		$donor = new Charitable_Donor( wp_get_current_user() );

		$user_fields = array(
			'first_name' => array( 
				'label' 	=> __( 'First name', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 4, 
				'required'	=> true, 
				'value'		=> $donor->first_name
			),
			'last_name' => array( 
				'label' 	=> __( 'Last name', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 6, 
				'required'	=> true, 
				'value'		=> $donor->last_name
			),
			'user_email' => array(
				'label' 	=> __( 'Email', 'charitable' ), 
				'type'		=> 'email',
				'required' 	=> true, 
				'priority'	=> 8, 
				'value' 	=> $donor->user_email
			),
			'company' => array(
				'label' 	=> __( 'Company', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 10, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_company' )
			),
			'address' => array( 
				'label' 	=> __( 'Address', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 12, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_address' )
			),
			'address_2' => array( 
				'label' 	=> __( 'Address 2', 'charitable' ), 
				'type'		=> 'text', 
				'priority' 	=> 14, 
				'required'	=> false,			
				'value' 	=> $donor->get( 'donor_address_2' )
			),
			'city' => array( 
				'label' 	=> __( 'City', 'charitable' ), 			
				'type'		=> 'text', 
				'priority'	=> 16, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_city' )
			),
			'state' => array( 
				'label' 	=> __( 'State', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 18, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_state' )
			),
			'postcode' => array( 
				'label' 	=> __( 'Postcode / ZIP code', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 20, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_postcode' )
			),
			'country' => array( 
				'label' 	=> __( 'Country', 'charitable' ), 				
				'type'		=> 'select', 
				'options' 	=> charitable_get_location_helper()->get_countries(), 
				'priority'	=> 22, 
				'required'	=> false, 
				'value' 	=> $donor->get( 'donor_country' )
			),
			'phone' => array( 
				'label' 	=> __( 'Phone', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 24, 
				'required'	=> false, 
				'value'		=> $donor->get( 'donor_phone' )
			)
		);	

		$user_fields = apply_filters( 'charitable_user_profile_fields', $user_fields );

		uasort( $user_fields, 'charitable_priority_sort' );

		return $user_fields;
	}
}

endif; // End class_exists check