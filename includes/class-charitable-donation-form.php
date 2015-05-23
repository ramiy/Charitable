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
		add_action( 'charitable_login_form', 						array( $this, 'login_form' ) );
		add_action( 'charitable_donation_form_before_donation_amount', array( $this, 'enter_donation_amount_header' ) );
		add_action( 'charitable_donation_form_amount', 				array( $this, 'enter_donation_amount' ) );
		add_action( 'charitable_donation_form_before_user_fields',	array( $this, 'add_hidden_fields' ) ); 
		add_action( 'charitable_donor_details', 					array( $this, 'add_donor_details' ) );
		add_action( 'charitable_donation_form_user_fields', 		array( $this, 'add_user_fields' ) ); 
		add_action( 'charitable_donation_form_user_field', 			array( $this, 'render_field' ), 10, 3 );		
		add_action( 'charitable_donation_form_after_user_fields', 	array( $this, 'add_password_field' ) );
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
		$user = wp_get_current_user();

		$user_fields = array(
			'first_name' => array( 
				'label' 	=> __( 'First name', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 4, 
				'required'	=> true, 
				'value'		=> ''
			),
			'last_name' => array( 
				'label' 	=> __( 'Last name', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 6, 
				'required'	=> true, 
				'value'		=> ''
			),
			'address' => array( 
				'label' 	=> __( 'Address', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 10, 
				'required'	=> false
			),
			'address_2' => array( 
				'label' 	=> __( 'Address 2', 'charitable' ), 
				'type'		=> 'text', 
				'priority' 	=> 12, 
				'required'	=> false
			),
			'city' => array( 
				'label' 	=> __( 'City', 'charitable' ), 			
				'type'		=> 'text', 
				'priority'	=> 14, 
				'required'	=> false
			),
			'state' => array( 
				'label' 	=> __( 'State', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 16, 
				'required'	=> false
			),
			'postcode' => array( 
				'label' 	=> __( 'Postcode / ZIP code', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 18, 
				'required'	=> false
			),
			'country' => array( 
				'label' 	=> __( 'Country', 'charitable' ), 				
				'type'		=> 'select', 
				'options' 	=> charitable_get_location_helper()->get_countries(), 
				'priority'	=> 20, 
				'required'	=> false
			),
			'phone' => array( 
				'label' 	=> __( 'Phone', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 22, 
				'required'	=> false 
			)
		);
		
		/**
		 * Allow plugin/theme developers to add new fields or remove/edit any of the above fields.
		 */
		$user_fields = apply_filters( 'charitable_donor_fields', $user_fields, $this );

		/**
		 * Add the email field, which is required in the form.
		 */
		if ( ! isset( $user_fields['user_email'] ) ) {
			$email_field_priority = apply_filters( 'charitable_donor_email_field_priority', 8, $this );
			$user_fields['user_email'] = array(
				'label' 	=> __( 'Email', 'charitable' ), 
				'type'		=> 'email',
				'required' 	=> true, 
				'priority'	=> $email_field_priority
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
				'required'	=> true
			)
		);

		if ( apply_filters( 'charitable_donor_usernames', false ) ) {
			$account_fields['user_login'] = array(
				'label'		=> __( 'Username', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 2,
				'required'	=> true
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
		$template = charitable_template( 'donation-form/form-donation.php', false );
		$template->set_view_args( array( 'campaign' => $this->get_campaign() ) );
		$template->render();
	}

	/**
	 * Display the login form. 
	 *
	 * @param 	Charitable_Donation_Form 	$form
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
	 * @param 	Charitable_Donation_Form 	$form
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
	 * @param 	Charitable_Donation_Form 	$form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function enter_donation_amount( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		$template = charitable_template( 'donation-form/donation-amount.php', false );
		$template->set_view_args( array( 'form' => $this, 'campaign' => $this->campaign ) );
		$template->render();
	}

	/**
	 * Adds hidden fields to the start of the donation form.	
	 *
	 * @param 	Charitable_Donation_Form 	$form
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
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_donor_details( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		$template = charitable_template( 'donation-form/donor-details.php', false );
		$template->set_view_args( array( 'form' => $this ) );
		$template->render();
	}

	/**
	 * Add user fields to the donation form. 
	 *
	 * @param 	Charitable_Donation_Form 	$form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_user_fields( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		$template = charitable_template( 'donation-form/user-fields.php', false );
		$template->set_view_args( array( 'form' => $this ) );
		$template->render();
	}

	/**
	 * Add a password field to the end of the form.  
	 *
	 * @param 	Charitable_Donation_form 	$form
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
	 * @return 	int|false 		If successful, this returns the donation ID. If unsuccessful, returns false.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function save_donation() {
		if ( ! $this->validate_nonce() ) {
			return false;
		}

		$values = array();		
		
		/* Set the donation amount */
		$values[ 'amount' ] = $this->get_donation_amount();

		if ( 0 == $values[ 'amount' ] && ! apply_filters( 'charitable_permit_empty_donations', false ) ) {
			
			charitable_get_notices()->add_error( __( 'No donation amount was set.', 'charitable' ) );
			return false;
		}
		
		/* Set all the user fields and make sure that all required fields were submitted */
		$user_fields = array_merge( $this->get_user_fields(), $this->get_user_account_fields() );

		foreach ( $form->get_required_fields( $user_fields ) as $key => $field ) {

			if ( ! isset( $_POST[ $key ] ) || empty( $_POST[ $key ] ) ) {
				/**
				 * @todo Provide useful feedback.
				 */
				return;
			}

		}	

		/* Save the user. This will insert new users and update existing ones. */
		$user = new Charitable_User( wp_get_current_user() );
		$user->save( $_POST, array_keys( $user_fields ) );
		$user->make_donor();
		
		$values[ 'user_id' ] = $user_id;

		/**
		 * @todo 	Add payment gateway. 
		 */
		$values[ 'gateway' ] = 'manual';		

		$values = apply_filters( 'charitable_donation_values', $values ); 

		$donation_id = Charitable_Donation::insert( $values );

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
}

endif; // End class_exists check