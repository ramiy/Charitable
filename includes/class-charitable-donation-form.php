<?php
/**
 * Donation form model class.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Donation_Form
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Form' ) ) : 

/**
 * Charitable_Donation_Form
 *
 * @since		1.0.0
 */
class Charitable_Donation_Form extends Charitable_Form implements Charitable_Donation_Form_Interface {

	/** 
	 * @var 	Charitable_Campaign
	 */
	protected $campaign;

	/**
	 * @var 	array
	 */
	protected $form_fields;

	/**
	 * @var 	string
	 */
	protected $nonce_action = 'charitable_donation';

	/**
	 * @var 	string
	 */
	protected $nonce_name = '_charitable_donation_nonce';

	/**
	 * Action to be executed upon form submission. 
	 *
	 * @var 	string
	 * @access  protected
	 */
	protected $form_action = 'make_donation';

	/**
	 * Create a donation form object.
	 *
	 * @param 	Charitable_Campaign $campaign
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct( Charitable_Campaign $campaign ) {
		$this->campaign = $campaign;
		$this->id 		= uniqid();	

		$this->attach_hooks_and_filters();	
	}

	/**
	 * Set up callbacks for actions and filters. 
	 *
	 * @return 	void
	 * @access  protected
	 * @since 	1.0.0
	 */
	protected function attach_hooks_and_filters() {
		parent::attach_hooks_and_filters();

		add_action( 'charitable_login_form', array( $this, 'login_form' ) );
		add_action( 'charitable_donation_form_before_donation_amount', array( $this, 'enter_donation_amount_header' ) );
		add_action( 'charitable_donation_form_amount', array( $this, 'enter_donation_amount' ) );		
		add_action( 'charitable_donor_details', array( $this, 'add_donor_details' ) );
		add_action( 'charitable_donation_form_user_fields', array( $this, 'add_user_fields' ) ); 
		add_action( 'charitable_donation_form_after_user_fields', array( $this, 'add_password_field' ) );		
	}

	/**
	 * Returns the campaign associated with this donation form object. 
	 *
	 * @return 	Charitable_Campaign
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaign() {
		return $this->campaign;
	}

	/**
	 * Returns the fields related to the person making the donation. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_user_fields() {
		$user = new Charitable_User( wp_get_current_user() );

		$user_fields = array(
			'first_name' => array( 
				'label' 	=> __( 'First name', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 4, 
				'value'		=> $user->get( 'first_name' ), 
				'required'	=> true, 				
				'requires_registration' => false
			),
			'last_name' => array( 
				'label' 	=> __( 'Last name', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 6, 
				'value'		=> $user->get( 'last_name' ), 
				'required'	=> true, 				
				'requires_registration' => false
			),
			'address' => array( 
				'label' 	=> __( 'Address', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 10, 
				'value'		=> $user->get( 'donor_address' ), 
				'required'	=> false, 
				'requires_registration' => true
			),
			'address_2' => array( 
				'label' 	=> __( 'Address 2', 'charitable' ), 
				'type'		=> 'text', 
				'priority' 	=> 12, 
				'value'		=> $user->get( 'donor_address_2' ), 
				'required'	=> false,
				'requires_registration' => true
			),
			'city' => array( 
				'label' 	=> __( 'City', 'charitable' ), 			
				'type'		=> 'text', 
				'priority'	=> 14, 
				'value'		=> $user->get( 'donor_city' ), 
				'required'	=> false,
				'requires_registration' => true
			),
			'state' => array( 
				'label' 	=> __( 'State', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 16, 
				'value'		=> $user->get( 'donor_state' ), 
				'required'	=> false,
				'requires_registration' => true
			),
			'postcode' => array( 
				'label' 	=> __( 'Postcode / ZIP code', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 18, 
				'value'		=> $user->get( 'donor_postcode' ), 
				'required'	=> false,
				'requires_registration' => true
			),
			'country' => array( 
				'label' 	=> __( 'Country', 'charitable' ), 				
				'type'		=> 'select', 
				'options' 	=> charitable_get_location_helper()->get_countries(), 
				'priority'	=> 20, 
				'value'		=> $user->get( 'donor_country' ), 
				'required'	=> false,
				'requires_registration' => true
			),
			'phone' => array( 
				'label' 	=> __( 'Phone', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 22, 
				'value'		=> $user->get( 'donor_phone' ), 
				'required'	=> false,
				'requires_registration' => true 
			)
		);
		
		/* Allow plugin/theme developers to add new fields or remove/edit any of the above fields. */
		$user_fields = apply_filters( 'charitable_donor_fields', $user_fields, $this );

		/* Add the email field, which is required in the form. */
		if ( ! isset( $user_fields['user_email'] ) ) {

			$email_field_priority = apply_filters( 'charitable_donor_email_field_priority', 8, $this );

			$user_fields['user_email'] = array(
				'label' 	=> __( 'Email', 'charitable' ), 
				'type'		=> 'email',
				'required' 	=> true, 
				'priority'	=> $email_field_priority,
				'value'		=> $user->get( 'user_email' ), 
				'requires_registration' => false
			);
			
		}

		uasort( $user_fields, 'charitable_priority_sort' );

		return $user_fields;
	}

	/**
	 * Return fields used for account creation. 
	 *
	 * By default, this just returns the password field. You can include a username
	 * field with ... 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_user_account_fields() {
		$account_fields = array(
			'user_pass' => array(
				'label'		=> __( 'Password', 'charitable' ), 
				'type'		=> 'password', 
				'priority'	=> 4, 
				'required'	=> true,
				'requires_registration' => true
			)
		);

		if ( apply_filters( 'charitable_donor_usernames', false ) ) {
			$account_fields['user_login'] = array(
				'label'		=> __( 'Username', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 2,
				'required'	=> true,
				'requires_registration' => true
			);
		}

		return $account_fields;
	}

	/**
	 * Render the donation form. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function render() {
		charitable_template( 'donation-form/form-donation.php', array( 
			'campaign' => $this->get_campaign(), 
			'form' => $this 
		) );
	}

	/**
	 * Display the login form. 
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function login_form( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}		

		charitable_template( 'donation-form/form-login.php' );
	}

	/**
	 * Add header before donation amount section.
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function enter_donation_amount_header( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}
		charitable_template( 'donation-form/donation-amount-header.php' );		
	}	

	/**
	 * Add fields to select or enter donation amount. 
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function enter_donation_amount( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}		

		charitable_template( 'donation-form/donation-amount.php', array( 
			'form' => $this, 
			'campaign' => $this->campaign 
		) );
	}

	/**
	 * Adds hidden fields to the start of the donation form.	
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_hidden_fields( $form ) {	
		if ( false === parent::add_hidden_fields( $form ) ) {
			return false;
		}	

		?>				
		<input type="hidden" name="campaign_id" value="<?php echo $this->campaign->ID ?>" />
		<?php
	}

	/**
	 * Add current donor details to the donation form. 
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_donor_details( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		charitable_template( 'donation-form/donor-details.php', array( 
			'form' => $this 
		) );
	}

	/**
	 * Add user fields to the donation form. 
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_user_fields( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		charitable_template( 'donation-form/user-fields.php', array( 
			'form' => $this 
		) );
	}

	/**
	 * Add a password field to the end of the form.  
	 *
	 * @param 	Charitable_Donation_Form $form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_password_field( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		/**
		 * Make sure we are not logged in.
		 */
		if ( 0 !== wp_get_current_user()->ID ) {
			return;
		}

		charitable_template_part( 'donation-form/user-login-fields' );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return 	int|false 	If successful, this returns the donation ID. If unsuccessful, returns false.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function save_donation() {
		if ( ! $this->validate_nonce() ) {
			return false;
		}	

		$amount = self::get_donation_amount();
		
		if ( 0 == $amount && ! apply_filters( 'charitable_permit_empty_donations', false ) ) {
			charitable_get_notices()->add_error( __( 'No donation amount was set.', 'charitable' ) );
			return false;
		}
		
		$user_fields = array_merge( $this->get_user_fields(), $this->get_user_account_fields() );

		if ( $this->is_missing_required_fields( $user_fields ) ) {
			return false;
		}

		/* Update the user's profile */
		$user = new Charitable_User( wp_get_current_user() );

		if ( $this->has_profile_fields( $_POST, $user_fields ) ) {			
			$user->update_profile( $_POST, array_keys( $user_fields ) );
		}

		$values = array(			
			'user_id' 	=> $user->ID,
			'gateway' 	=> 'manual', 
			'campaigns' => array(
				array(
					'campaign_id' 	=> $_POST[ 'campaign_id' ],
					'amount'	 	=> $amount
				)				
			)
		);

		$values = array_merge( $values, $this->get_donor_value_fields( $_POST ) );

		$values = apply_filters( 'charitable_donation_values', $values );

		$donation_id = Charitable_Donation::add_donation( $values );

		return $donation_id;
	}

	/**
	 * Return the donation amount.  
	 *
	 * @return 	float
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_donation_amount() {
		$suggested 	= isset( $_POST[ 'donation-amount' ] ) ? $_POST[ 'donation-amount' ] : 0;
		$custom 	= isset( $_POST[ 'custom-donation-amount' ] ) ? $_POST[ 'custom-donation-amount' ] : 0;

		if ( 0 === $suggested || 'custom' === $suggested ) {

			$amount = $custom;

		} 
		else {

			$amount = $suggested;

		}

		return $amount;
	}

	/**
	 * Return the donor value fields. 
	 *
	 * @return  string[]
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function get_donor_value_fields( $submitted ) {
		$donor_fields = array();

		if ( isset( $submitted[ 'first_name' ] ) ) {
			$donor_fields[ 'first_name' ] = $submitted[ 'first_name' ];
		}

		if ( isset( $submitted[ 'last_name' ] ) ) {
			$donor_fields[ 'last_name' ] = $submitted[ 'last_name' ];
		}

		if ( isset( $submitted[ 'user_email' ] ) ) {
			$donor_fields[ 'email' ] = $submitted[ 'user_email' ];
		}

		return $donor_fields;
	}

	/**
	 * Checks whether the form submission contains profile fields.  
	 *
	 * @return  boolean
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function has_profile_fields( $submitted, $user_fields ) {
		foreach ( $user_fields as $key => $field ) {
			if ( $field[ 'requires_registration' ] && isset( $submitted[ $key ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns true if required fields are missing. 
	 *
	 * @param 	array 	$required_fields
	 * @return  boolean
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function is_missing_required_fields( $required_fields ) {
		if ( is_user_logged_in() ) {
			return false;
		}

		return ! $this->check_required_fields( $required_fields );
	}
}

endif; // End class_exists check