<?php
/**
 * Donation form model class.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Donation_Form
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Form' ) ) : 

/**
 * Charitable_Donation_Form
 *
 * @since		1.0.0
 */
class Charitable_Donation_Form implements Charitable_Donation_Form_Interface {

	/** 
	 * @var 	Charitable_Campaign
	 */
	private $campaign;

	/**
	 * Temporary, unique ID of this form. 
	 *
	 * @var 	string
	 * @access  private
	 */
	private $id;

	/**
	 * @var 	array
	 */
	private $form_fields;

	/**
	 * @var 	string
	 */
	private $nonce_action = 'charitable_donation';

	/**
	 * @var 	string
	 */
	private $nonce_name = '_charitable_donation_nonce';

	/**
	 * Create a donation form object.
	 *
	 * @param 	Charitable_Campaign $campaign
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct( Charitable_Campaign $campaign ) {
		$this->campaign = $campaign;
		$this->id 		= uniqid();

		add_action( 'charitable_login_form', 						array( $this, 'login_form' ) );
		add_action( 'charitable_donation_form_amount', 				array( $this, 'enter_donation_amount' ) );
		add_action( 'charitable_donation_form_before_user_fields',	array( $this, 'add_hidden_fields' ) ); 
		add_action( 'charitable_donation_form_user_field', 			array( $this, 'render_field' ), 10, 3 );		
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
	 * Compares the ID of the form passed by the action and the current form object to ensure they're the same. 
	 *
	 * @param 	string 		$id
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function is_current_form( $id ) {
		return $id === $this->id;
	}

	/**
	 * Returns the fields related to the person making the donation. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_user_fields() {
		$user_fields = array(
			'first_name' => array( 
				'label' 	=> __( 'First name', 'charitable' ), 
				'type'		=> 'text', 
				'priority'	=> 4, 
				'required'	=> true
			),
			'last_name' => array( 
				'label' 	=> __( 'Last name', 'charitable' ), 				
				'type'		=> 'text', 
				'priority'	=> 6, 
				'required'	=> true
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
				'options' 	=> get_charitable()->get_location_helper()->get_countries(), 
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
		$user_fields = apply_filters( 'charitable_donation_form_user_fields', $user_fields, $this );

		/**
		 * Add the email field, which is required in the form.
		 */
		if ( ! isset( $user_fields['email'] ) ) {
			$email_field_priority = apply_filters( 'charitable_donation_form_user_email_field_priority', 8, $this );
			$user_fields['email'] = array(
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
	 * Whether the given field type can use the default field template. 
	 *
	 * @param 	string 		$field_type
	 * @return 	boolean
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function use_default_field_template( $field_type ) {
		$default_field_types = apply_filters( 'charitable_default_template_field_types', array( 
			'text', 'url', 'email', 'password' 
		) );
		return in_array( $field_type, $default_field_types );
	}

	/**
	 * Render the donation form. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function render() {
		charitable_template_part( 'donation-form/form-donation' );
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

		charitable_template_part( 'donation-form/form-login' );
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

		charitable_template_part( 'donation-form/donation-amount' );
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
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		$this->nonce_field();
		?>				
		<input type="hidden" name="campaign_id" value="<?php echo $this->campaign->get_campaign_id() ?>" />
		<input type="hidden" name="charitable_action" value="make-donation" />
		<?php
	}

	/**
	 * Render a form field. 
	 *
	 * @param 	array 	$field
	 * @param 	string 	$key
	 * @param 	Charitable_Donation_Form 	$form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function render_field( $field, $key, $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return;
		}

		if ( ! isset( $field['type'] ) ) {
			return;
		}

		$this->current_field = $field;
		$this->current_field['key'] = $key;

		/**
		 * Many field types, like text, email, url, etc fall 
		 * back to the default-field template. 
		 */
		$field_type = $field['type'];			
		if ( $this->use_default_field_template( $field_type ) ) {
			$this->current_field['type'] = $field_type;
			$field_type = 'default';
		}

		$template_name = 'donation-form/' . $field_type . '-field';

		charitable_template_part( $template_name );
	}

	/**
	 * Returns the current field being displayed. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_current_field() {
		return $this->current_field;
	}

	/**
	 * Output the nonce. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function nonce_field() {
		wp_nonce_field( $this->nonce_action, $this->nonce_name );
	}

	/** 
	 * Validate nonce data passed by the submitted form. 
	 * 
	 * @return 	boolean
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function validate_nonce() {
		return isset( $_POST[$this->nonce_name] ) && wp_verify_nonce( $_POST[$this->nonce_name], $this->nonce_action );
	}

	/**
	 * Save the submitted donation.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function save_donation() {
		if ( ! $this->validate_nonce() ) {
			/**
			 * @todo 	Set error message.
			 */
			return;
		}

		$values = array();

		/**
		 * Set the donation amount.
		 */
		if ( ! isset( $_POST['donation-amount'] ) ) {
			/**
			 * @todo 	Set error message.
			 */
			return;
		}


		$donation_amount = $_POST['donation-amount'];

		if ( 'custom' == $donation_amount ) {

			if ( ! isset( $_POST['custom-donation-amount'] ) ) {
				/**
				 * @todo 	Set error message.
				 */
				return;
			}

			$values['amount'] = $_POST['custom-donation-amount'];
			$values['is_preset_amount'] = 0;
		}
		else {
			$values['amount'] = $donation_amount;
			$values['is_preset_amount'] = 1;
		}

		/**
		 * Set all the user fields. 
		 */
		foreach ( $this->get_user_fields() as $key => $field ) {

			if ( isset( $_POST[$key] ) ) {
				$values['user'][$key] = $_POST[$key];
			}
			else {
				/**
				 * If this was a required field, return an error message.
				 */
				if ( true === $field['required'] ) {
					/**
					 * @todo 	Set error message.
					 */
					return;
				}
			}			
		}

		/**
		 * @todo 	Add payment gateway. 
		 */
		$values['gateway'] = 'manual';

		$this->campaign->add_donation( $values );

		// echo '<pre>'; 
		// print_r( $_POST ); 
		// die;
	}
}

endif; // End class_exists check