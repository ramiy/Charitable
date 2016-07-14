<?php
/**
 * Charitable User Management Hooks
 *
 * @package     Charitable/User Management/User Management
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Fire off the password reset request.
 *
 * @see     Charitable_Forgot_Password_Form::retrieve_password()
 */
add_action( 'charitable_retrieve_password', array( 'Charitable_Forgot_Password_Form', 'retrieve_password' ) );

/**
 * Reset a user's password.
 *
 * @see     Charitable_Reset_Password_Form::reset_password()
 */
add_action( 'charitable_reset_password', array( 'Charitable_Reset_Password_Form', 'reset_password' ) );

/**
 * Redirect the user to the password reset page with the query string removed.
 *
 * @see     Charitable_User_Management::maybe_redirect_to_password_reset()
 */
add_action( 'template_redirect', array( Charitable_User_Management::get_instance(), 'maybe_redirect_to_password_reset' ) );

/**
 * Hides the WP Admin bar if the current user is not allowed to view it.
 *
 * @see Charitable_User_Management::remove_admin_bar()
 */
add_action( 'after_setup_theme', array( Charitable_User_Management::get_instance(), 'maybe_remove_admin_bar' ) );

/**
 * Redirects the user away from /wp-admin if they are not authorized to access it.
 *
 * @see     Charitable_User_Management::maybe_redirect_away_from_admin()
 */
add_action( 'admin_init', array( Charitable_User_Management::get_instance(), 'maybe_redirect_away_from_admin' ) );

/**
 * If desired, all access to wp-login.php can be redirected to the Charitable login page.
 *
 * This is switched off by default. To enable this option, you need to set a Charitable
 * login page and also return true for the filter:
 *
 * add_filter( 'charitable_disable_wp_login', '__return_true' );
 *
 * @see     Charitable_User_Management::redirect_to_charitable_login()
 */
if ( apply_filters( 'charitable_disable_wp_login', false ) && 'wp' != charitable_get_option( 'login_page', 'wp' ) ) {

	add_action( 'login_init', array( Charitable_User_Management::get_instance(), 'redirect_to_charitable_login' ) );

}

// /**
//  * Redirect user from wp-login.php to charitable login page if Hide Default
//  * WP Login Page is selected in the settings
//  *
//  * @see Charitable_User_Management::prevent_wp_login()
//  */
// add_action( 'login_form_login', array( 'Charitable_User_Management', 'prevent_wp_login' ) );

// *
//  * Redirect user from wp-login.php to charitable login page, upon failed auth,
//  * if Hide Default WP Login Page is selected in the settings
//  *
//  * @see Charitable_User_Management::maybe_redirect_at_authenticate()

// add_filter( 'authenticate', array( 'Charitable_User_Management', 'maybe_redirect_at_authenticate' ) , 101, 3 );

// /**
//  * On the login page, look in the session for login error messages or notices
//  * and display them if they are present.
//  *
//  * @see Charitable_User_Management::get_login_errors_from_session()
//  * @see charitable_login_form_before
//  */
// add_action( 'charitable_login_form_before', array( 'Charitable_User_Management', 'get_login_errors_from_session' ) );


