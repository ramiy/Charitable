<?php
/**
 * Class that manages the hook functions for the forgot password form.
 *
 * @package     Charitable/User Management/Forgot Password
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Forgot_Password' ) ) :

  /**
   * Charitable_Forgot_Password class
   *
   * @since       1.4.0
   */
  class Charitable_Forgot_Password {

    /**
     * A callback method for the login_form_lostpassword WordPress action
     *
     * Triggered when the user requests the forgot password page. Redirects the
     * user to the forgot password page selected in charitable settings if that
     * page differs from the default WordPress page.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function redirect_to_custom_lostpassword() {
      if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        $page = charitable_get_permalink( 'forgot_password_page' );

        if ( $page != wp_lostpassword_url() ) {
          wp_redirect( $page );
          exit;
        }
      }
    }

    /**
     * A callback method for the login_form_lostpassword WordPress action
     *
     * Triggered when the user POSTs to the forgot password page. Calls the
     * builtin `retrieve_password` function.  Calls the WordPress
     * retrieve_password function.
     *
     * On success, redirects user to login page, displays notice.
     *
     * On failure, redirects user back to forgot password page, displays error notice.
     *
     * @access public
     * @static
     * @since 1.4.0
     * @see retrieve_password()
     */
    public static function do_password_lost() {
      if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $errors = retrieve_password();

        if ( is_wp_error( $errors ) ) {
          $redirect_url = charitable_get_permalink( 'forgot_password_page' );
          charitable_get_session()->set( 'forgot_password_errors', $errors->get_error_messages() );
        } else {
          $redirect_url = charitable_get_permalink( 'login_page' );
          charitable_get_session()->set( 'checkmail', array( __( 'Check your email for a link to reset your password.', 'charitable' ) ) );
        }

        wp_redirect( $redirect_url );
        exit;
      }
    }

    /**
     * Look in the session for forgot password error messages or notices and
     * display them if they are present.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function get_errors_from_session() {
      if ( $errors = charitable_get_session()->get( 'forgot_password_errors' ) ) {
        charitable_get_session()->set( 'forgot_password_errors', null );
        charitable_template( 'form-fields/errors.php', array(
          'errors' => $errors,
          'form' => 'bogus'
        ) );
      }
    }

    /**
     * Look in the session for forgot password error messages or notices and
     * display them if they are present.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function check_for_password_reset_text() {
      if ( $messages = charitable_get_session()->get( 'checkmail' ) ) {
        charitable_get_session()->set( 'checkmail', null );
        charitable_template( 'form-fields/errors.php', array(
          'errors' => $messages,
          'form' => 'bogus'
        ) );
      }
    }
  }

endif;
