<?php
/**
 * A base class to be extended by specific form classes.
 *
 * @package		Charitable/Classes/Charitable_Form
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Form' ) ) : 

/**
 * Charitable_Form
 *
 * @abstract
 * @since 		1.0.0
 */
abstract class Charitable_Form {

	/**
	 * Temporary, unique ID of this form. 
	 *
	 * @var 	string
	 * @access  protected
	 */
	protected $id;

	/**
	 * @var 	string
	 * @access 	protected
	 */
	protected $nonce_action = 'charitable_form';

	/**
	 * @var 	string
	 * @access 	protected
	 */
	protected $nonce_name = '_charitable_form_nonce';

	/**
	 * Form action.  
	 *
	 * @var 	string
	 * @access  protected
	 */
	protected $form_action;

	/**
	 * Set up callbacks for actions and filters. 
	 *
	 * @return 	void
	 * @access  protected
	 * @since 	1.0.0
	 */
	protected function attach_hooks_and_filters() {
		add_action( 'charitable_form_before_fields',	array( $this, 'add_hidden_fields' ) ); 
		add_action( 'charitable_form_field', 			array( $this, 'render_field' ), 10, 3 );
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
	 * Whether the given field type can use the default field template. 
	 *
	 * @param 	string 		$field_type
	 * @return 	boolean
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function use_default_field_template( $field_type ) {
		$default_field_types = apply_filters( 'charitable_default_template_field_types', array( 
			'text', 
			'url', 
			'email', 
			'password' 
		) );
		return in_array( $field_type, $default_field_types );
	}

	/**
	 * Adds hidden fields to the start of the donation form.	
	 *
	 * @param 	Charitable_Form 	$form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_hidden_fields( $form ) {
		if ( ! $form->is_current_form( $this->id ) ) {
			return false;
		}

		$this->nonce_field();	

		?>
		<input type="hidden" name="charitable_action" value="<?php echo $this->form_action ?>" />	
		<?php
	}

	/**
	 * Render a form field. 
	 *
	 * @param 	array 	$field
	 * @param 	string 	$key
	 * @param 	Charitable_Form 	$form
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function render_field( $field, $key, $form ) {		
		if ( ! $form->is_current_form( $this->id ) ) {
			return false;
		}

		if ( ! isset( $field['type'] ) ) {
			return false;
		}		

		/**
		 * Many field types, like text, email, url, etc fall 
		 * back to the default-field template. 
		 */				
		$field_template = $this->use_default_field_template( $field[ 'type' ] ) ? 'default' : $field[ 'type' ];	
		$template_name	= sprintf( 'form-fields/%s-field.php', $field_template );
		$field[ 'key' ] = $key;

		/**
		 * Display template, passing the form and field objects as parameters to the view.
		 */
		$template = charitable_template( $template_name, false );
		$template->set_view_args( array(
			'form' 	=> $this, 
			'field' => $field
		) );
		$template->render();
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
}

endif; // End class_exists check