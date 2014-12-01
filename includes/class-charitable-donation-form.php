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
	 * When rendering the form, this field is used to pass the field information through to the template. 
	 * 
	 * @var 	array
	 */
	private $current_field = array();

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
		 * The email field is the only field that is absolutely required.
		 */
		$email_field_priority = apply_filters( 'charitable_donation_form_email_field_priority', 8, $this );
		$form_fields['email'] = array(
			'label' 	=> __( 'Email', 'charitable' ), 
			'type'		=> 'email',
			'required' 	=> true, 
			'priority'	=> $email_field_priority
		);

		uasort( $form_fields, 'charitable_priority_sort' );

		return $form_fields;
	}

	/** 
	 * Returns the current field being rendered.
	 *
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_current_field() {
		return $this->current_field;
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
		?>
		<form method="post" class="charitable-donation-form">
			<?php $this->nonce_field() ?>
			<input type="hidden" name="campaign_id" value="<?php echo $this->campaign->get_campaign_id() ?>" />
			<input type="hidden" name="charitable_action" value="make-donation" />
			<?php foreach ( $this->get_fields() as $key => $field ) :

				if ( ! isset( $field['type'] ) ) {
					continue;
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

				new Charitable_Template_Part( $template_name );

			endforeach 
			?>
			<input type="submit" name="charitable_submit" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" class="button button-primary" />
		</form>
		<?php
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
			return;
		}

		echo '<pre>'; 

		echo 'hello';
		print_r( $_POST ); 
		die;
	}
}

endif; // End class_exists check