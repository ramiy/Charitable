<?php
/**
 * Charitable Reset Password Hooks
 *
 * @package     Charitable/User Management/Reset Password
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Redirects user to reset password page selected in settings if it is
 * different from default page.  Also, verify that login and key params in url
 * are valid
 *
 * @see Charitable_Reset_Password::redirect_to_custom_password_reset()
 */
add_action( 'login_form_rp', array( 'Charitable_Reset_Password', 'redirect_to_custom_password_reset' ) );
add_action( 'login_form_resetpass', array( 'Charitable_Reset_Password', 'redirect_to_custom_password_reset' ) );

/**
 * Handle submission of the reset password form.
 *
 * @see Charitable_Reset_Password::handle_password_reset()
 */
add_action( 'login_form_rp', array( 'Charitable_Reset_Password', 'handle_password_reset' ) );
add_action( 'login_form_resetpass', array( 'Charitable_Reset_Password', 'handle_password_reset' ) );

/**
 * On the login, logged-in, forgot password, and reset password pages, check
 * for error messages in the session and display if present
 *
 * @see Charitable_Reset_Password::get_errors_from_session()
 *
 */
add_action( 'charitable_login_form_before', array( 'Charitable_Reset_Password', 'get_errors_from_session' ) );
add_action( 'charitable_logged_in_before', array( 'Charitable_Reset_Password', 'get_errors_from_session' ) );
add_action( 'charitable_forgot_password_before', array( 'Charitable_Reset_Password', 'get_errors_from_session' ) );
add_action( 'charitable_reset_password_before', array( 'Charitable_Reset_Password', 'get_errors_from_session' ) );

/**
 * On the login page, look in the session for reset password error messages and
 * display them if they are present.
 *
 * @see Charitable_Reset_Password::check_for_password_reset_text()
 * @see charitable_login_form_before
 */
add_action( 'charitable_login_form_before', array( 'Charitable_Reset_Password', 'check_for_password_reset_text' ) );
