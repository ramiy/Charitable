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
	}

	/**
	 * Returns the fields related to the person making the donation. 
	 *
	 * @return 	array
	 * @access  protected
	 * @since 	1.0.0
	 */
	protected function get_user_fields() {
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

		return $user_fields;
	}

	/**
	 * Returns the fields to be displayed in the donation form. 
	 *
	 * @uses 	charitable_donation_form_fields
	 * 
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_fields() {
		$form_fields = array(			
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
			),
			'comment' => array( 
				'label' 	=> __( 'Comment', 'charitable' ), 				
				'type'		=> 'textarea', 
				'priority'	=> 30, 
				'required'	=> false
			)
		);

		/**
		 * Allow plugin/theme developers to add new fields or remove/edit any of the above fields.
		 */
		$form_fields = apply_filters( 'charitable_donation_form_fields', $form_fields, $this );

		/**
		 * Add required fields, which we don't want people to remove through the filter above.
		 */
		// $required_fields = $this->get_required_fields();

		$form_fields = array_merge( $form_fields, $required_fields );

		usort( $form_fields, 'charitable_priority_sort' );

		return $form_fields;
	}

	/**
	 * Returns an array of fields that must be included in the donation form. 
	 *
	 * @return 	array
	 * @access  protected
	 * @since 	version
	 */
	protected function get_required_fields() {
		/**
		 * Campaign ID 
		 */
		$required_fields['campaign_id'] = array(
			'type'		=> 'hidden', 
			'priority'	=> 1, 
			'value'		=> $this->campaign->get_campaign_id()
		);

		/**
		 * Email field 
		 */
		$email_field_priority = apply_filters( 'charitable_donation_form_email_field_priority', 8, $this );
		$required_fields['email'] = array(
			'label' 	=> __( 'Email', 'charitable' ), 
			'type'		=> 'email',
			'required' 	=> true, 
			'priority'	=> $email_field_priority
		);

		/**
		 * Submit
		 */
		$required_fields['submit'] = array(
			'label'		=> __( 'Donate', 'charitable' ), 
			'type'		=> ''
		);

		return $required_fields;
	}

	/**
	 * Whether the given field type can use the default field template. 
	 *
	 * @param 	string $field_type
	 * @return 	boolean
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function use_default_field_template( $field_type ) {
		$non_default_field_types = apply_filters( 'charitable_non_default_template_field_types', array( 
			'select', 'textarea', 'radio', 'hidden' 
		) );
		return ! in_array( $field_type, $non_default_field_types );
	}

	/**
	 * Render the donation form. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function render() {
		$user = wp_get_current_user();

		echo '<pre>'; 
		print_r( $user ); 
		echo '</pre>';
		?>
		<form method="post" class="charitable-donation-form">
			<?php $this->nonce_field() ?>				
			<input type="hidden" name="campaign_id" value="<?php echo $this->campaign->get_campaign_id() ?>" />
			<?php
			/**
			 * Allow the donor to choose how much they would like to donate.
			 */

			/**
			 * User stuff. 
			 *
			 * If the user isn't logged in, display a login form. 
			 * If they are, display the information we have in store without showing the fields unless they opt to change them.
			 * If they are not logged in and they have no account, they will see the full list of user fields.
			 */


			/**
			 * If the user is not logged in, display a login prompt. 
			 */
			if ( 0 === $user->ID ) : 
			?>

			<?php
			else : 
				// Prompt the user to log in first.
				?>
			
			<?php endif ?>

			<!-- // If the user is logged in, pre-fill the user fields. -->

			<!-- // Otherwise, a new user account is created when the donation is made. -->

			?>
			<div class="charitable-form-field charitable-submit-field">
				<input class="button-primary" type="submit" name="donate" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" />
			</div>
		<?php
		// foreach ( $this->get_fields() as $key => $field ) {

		// 	if ( ! isset( $field['type'] ) ) {
		// 		continue;
		// 	}

		// 	$this->current_field = $field;
		// 	$this->current_field['key'] = $key;

			/**
			 * Many field types, like text, email, url, etc fall 
			 * back to the default-field template. 
			 */
		// 	$field_type = $field['type'];			
		// 	if ( $this->use_default_field_template( $field_type ) ) {
		// 		$this->current_field['type'] = $field_type;
		// 		$field_type = 'default';
		// 	}

		// 	$template_name = 'donation-form/' . $field_type . '-field';

		// 	new Charitable_Template_Part( $template_name );
		// }

		?>
		</form>
		<?php
	}	

	/**
	 * description 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function render_field( $field ) {
		
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
	 * @return void
	 * @access public
	 * @since 1.0.0
	 */
	public function save_donation() {
		if ( ! $this->validate_nonce() ) {

		}

		echo '<pre>'; 
		print_r( $_POST ); 
		die;
	}
}

endif; // End class_exists check