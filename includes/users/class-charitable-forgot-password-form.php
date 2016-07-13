<?php
/**
 * Class that manages the display and processing of the forgot password form.
 *
 * @package     Charitable/Classes/Charitable_Forgot_Password_Form
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Forgot_Password_Form' ) ) :

	/**
	 * Charitable_Forgot_Password_Form
	 *
	 * @since       1.4.0
	 */
	class Charitable_Forgot_Password_Form extends Charitable_Form {

		/**
		 * @var 	string
		 * @access 	protected
		 * @since 	1.4.0
		 */
		protected $nonce_action = 'charitable_reset_password';

		/**
		 * @var 	string
		 * @access 	protected
		 * @since 	1.4.0
		 */
		protected $nonce_name = '_charitable_reset_password_nonce';

		/**
		 * Form action.
		 *
		 * @var 	string
		 * @access  protected
		 * @since 	1.4.0
		 */
		protected $form_action = 'retrieve_password';

		/**
		 * Create class object.
		 *
		 * @param   array $args User-defined shortcode attributes.
		 * @access  public
		 * @since   1.4.0
		 */
		public function __construct() {
			$this->id = uniqid();
			// $this->shortcode_args = $args;
			$this->attach_hooks_and_filters();
		}

		/**
		 * Forgot password fields to be displayed.
		 *
		 * @return  array
		 * @access  public
		 * @since   1.4.0
		 */
		public function get_fields() {
			$fields = apply_filters( 'charitable_forgot_password_fields', array(
				'user_login' => array(
					'label'    => __( 'Email Address', 'charitable' ),
					'type'     => 'email',
					'required' => true,
					'priority' => 10,
				),
			) );

			uasort( $fields, 'charitable_priority_sort' );

			return $fields;
		}

		/**
		 * Send the password reset email.
		 *
		 * @global  wpdb         $wpdb      WordPress database abstraction object.
	     * @global  PasswordHash $wp_hasher Portable PHP password hashing framework.
	     *
		 * @return  bool|WP_Error True: when finish. WP_Error on error
		 * @access  public
		 * @static
		 * @since   1.4.0
		 */
		public static function retrieve_password() {
			global $wpdb, $wp_hasher;

			$form = new Charitable_Forgot_Password_Form();

			if ( ! $form->validate_nonce() ) {
				return;
			}

			$errors = new WP_Error();

			if ( empty( $_POST['user_login'] ) ) {

				$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.', 'charitable' ) );

			} elseif ( strpos( $_POST['user_login'], '@' ) ) {

				$user = get_user_by( 'email', trim( $_POST['user_login'] ) );

				if ( empty( $user ) ) {

					$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.', 'charitable' ) );

				}
			} else {

				$login = trim( $_POST['user_login'] );
				$user = get_user_by( 'login', $login );

			}

			do_action( 'lostpassword_post', $errors );

			/* If there are errors, proceed no further. */
			if ( $errors->get_error_code() ) {
				return $errors;
			}

			/* If we are missing user data, proceed no further. */
			if ( ! $user ) {
				$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: Invalid username or email.', 'charitable' ) );
				return $errors;
			}

			/* Prepare the email. */
			$email      = new Charitable_Email_Password_Reset( array( 'user' => $user ) );
			$reset_link = $email->get_reset_link();

			/* Make sure that the reset link was generated correctly. */
			if ( is_wp_error( $reset_link ) ) {
				return $reset_link;
			}

			$sent = $email->send();

			return $sent;

		}
	}

endif;
