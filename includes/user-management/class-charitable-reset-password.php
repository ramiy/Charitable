<?php
/**
 * Class that manages the hook functions for the forgot password form.
 *
 * @package     Charitable/User Management/Reset Password
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Reset_Password' ) ) :

  /**
   * Charitable_Reset_Password class
   *
   * @since       1.4.0
   */
  class Charitable_Reset_Password {

    /**
     * A callback method for the login_form_rp and login_form_resetpass
     * WordPress actions, handles GET requests
     *
     * Triggered when the user requests the reset password page. Redirects the
     * user to the reset password page selected in charitable settings if that
     * page differs from the default WordPress page. Redirects instead to the
     * forgot password page if key or login url parameters are invalid or
     * expired
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function redirect_to_custom_password_reset() {
      if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

        $redirect_url = charitable_get_permalink( 'reset_password_page' );

        /**
         * If we are using a custom forgot password page, explicitly check the
         * error and redirect, otherwise the error message will not be
         * displayed when we are redirected to the forgot password page.  If we
         * are using the default forgot password page, do not do anything so
         * WordPress can display the error.
         */
        if ( charitable_get_permalink( 'forgot_password_page' ) != wp_lostpassword_url() ) {
          $error_or_user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
          self::check_error_and_redirect( $error_or_user );
        }

        // Verify key / login combo
        if ( $redirect_url != wp_lostpassword_url() ) {
          // redirect to custom reset password page if successful
          $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
          $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
          wp_redirect( $redirect_url );
          exit;
        }
      }
    }

    /**
     * A callback method for the login_form_rp and login_form_resetpass
     * WordPress actions, handles POST requests
     *
     * Resets the user's password if the password reset form was submitted.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function handle_password_reset() {
      if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $redirect_url = charitable_get_permalink( 'reset_password_page' );

        if ( $redirect_url != wp_lostpassword_url() ) {
          $rp_key = $_REQUEST['rp_key'];
          $rp_login = $_REQUEST['rp_login'];

          $user_or_error = check_password_reset_key( $rp_key, $rp_login );
          self::check_error_and_redirect( $user_or_error );

          $redirect_url = add_query_arg( 'key', urlencode( $rp_key ), $redirect_url );
          $redirect_url = add_query_arg( 'login', urlencode( $rp_login ), $redirect_url );

          if ( isset( $_POST['pass1'] ) ) {
            // Passwords don't match
            if ( $_POST['pass1'] != $_POST['pass2'] ) {
              charitable_get_session()->set( 'reset_password_errors', array(
                __( "<strong>ERROR:</strong> The two passwords you entered don't match.", 'charitable' )
              ) );

              wp_redirect( $redirect_url );
              exit;
            }

            // Password is empty
            if ( empty( $_POST['pass1'] ) ) {
              charitable_get_session()->set( 'reset_password_errors', array(
                __( "<strong>ERROR:</strong> Password must not be empty.", 'charitable' )
              ) );

              wp_redirect( $redirect_url );
              exit;
            }

            // Parameter checks OK, reset password
            reset_password( $user_or_error, $_POST['pass1'] );
            $redirect_url = charitable_get_permalink( 'login_page' );
            charitable_get_session()->set( 'password', array(
              __( 'Your password was successfully changed.', 'charitable' ) )
            );
          } else {
            charitable_get_session()->set( 'reset_password_errors', array(
              __( "<strong>ERROR:</strong> Bad Request", 'charitable' )
            ) );
          }

          wp_redirect( $redirect_url );
          exit;
        }
      }
    }

    /**
     * Check on type of error message and redirect as needed
     *
     * If user is attempting to reach reset password with invalid or expired
     * key, add appropriate message to the session and redirect user to the
     * forgot password page to resubmit form.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function check_error_and_redirect( $error ) {
      # $error should always be a WP_User or a WP_Error.  If it is null,
      # something went wrong with the request
      if ( ! $error || is_wp_error( $error ) ) {
        $redirect_url = charitable_get_permalink( 'forgot_password_page' );

        if ( $error ) {
          if ( $error->get_error_code() === 'expired_key' ) {
            $error->remove( 'expired_key' );
            $error->add( 'expired_key', __( 'Your password reset link has expired. Please request a new link below.', 'charitable' ) );
          } elseif ( $error->get_error_code() === 'invalid_key' ) {
            $error->remove( 'invalid_key' );
            $error->add( 'invalid_key', __( 'Your password reset link appears to be invalid. Please request a new link below.', 'charitable' ) );
          }
          charitable_get_session()->set( 'reset_password_errors', $error->get_error_messages() );
        } else {
          # should not be reached
          charitable_get_session()->set( 'reset_password_errors', __( 'There was an error with your request. Please try again.', 'charitable' ) );
        }

        wp_redirect( $redirect_url );
        exit;
      }
    }

    /**
     * Look in the session for reset password error messages or notices and
     * display them if they are present.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function get_errors_from_session() {
      if ( $errors = charitable_get_session()->get( 'reset_password_errors' ) ) {
        charitable_get_session()->set( 'reset_password_errors', null );
        charitable_template( 'form-fields/errors.php', array(
          'errors' => $errors,
          'form' => 'bogus'
        ) );
      }
    }

    /**
     * Look in the session for reset password error messages or notices and
     * display them if they are present.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function check_for_password_reset_text() {
      if ( $messages = charitable_get_session()->get( 'password' ) ) {
        charitable_get_session()->set( 'password', null );
        charitable_template( 'form-fields/errors.php', array(
          'errors' => $messages,
          'form' => 'bogus'
        ) );
      }
    }

  }

endif;
