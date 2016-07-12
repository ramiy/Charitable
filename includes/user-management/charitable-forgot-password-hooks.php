<?php
/**
 * Charitable Forgot Password Hooks
 *
 * @package     Charitable/User Management/Forgot Password
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Redirects user to forgot password page selected in settings if it is different from default page.
 *
 * @see Charitable_Forgot_Password::redirect_to_custom_lostpassword()
 */
add_action( 'login_form_lostpassword', array( 'Charitable_Forgot_Password', 'redirect_to_custom_lostpassword' ) );

/**
 * Handle submission of the forgot password form.  If the reset is successful,
 * user is redirected to the login page and a notice is displayed.  If the
 * reset is not successful, user is redirected back to the forgot password page
 * and the WordPress error message is displayed.
 *
 * @see Charitable_Forgot_Password::do_lost_password()
 */
add_action( 'login_form_lostpassword', array( 'Charitable_Forgot_Password', 'do_password_lost' ) );

/**
 * On the login page, look in the session for forgot password error messages
 * and display them if they are present.
 *
 * @see Charitable_Forgot_Password::check_for_password_reset_text()
 * @see charitable_login_form_before
 */
add_action( 'charitable_login_form_before', array( 'Charitable_Forgot_Password', 'check_for_password_reset_text' ) );

/**
 * On the forgot password page, look in the session for forgot password error
 * messages or notices and display them if they are present.
 *
 * @see Charitable_Forgot_Password::get_errors_from_session()
 * @see charitable_forgot_password_before
 */
add_action( 'charitable_forgot_password_before', array( 'Charitable_Forgot_Password', 'get_errors_from_session' ) );
