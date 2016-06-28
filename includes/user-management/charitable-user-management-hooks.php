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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Hides the WP Admin bar if the current user is not allowed to view it.
 *
 * @see Charitable_User_Management::remove_admin_bar()
 */
add_action( 'after_setup_theme', array( 'Charitable_User_Management', 'remove_admin_bar' ) );

/**
 * Returns a 404 response if the current user attempts to visit /wp-admin and is not authorized
 *
 * @see Charitable_User_Management::blockusers_init()
 */
add_action( 'admin_init', array( 'Charitable_User_Management', 'blockusers_init' ) );


/**
 * Redirect user from wp-login.php to charitable login page if Hide Default
 * WP Login Page is selected in the settings
 *
 * @see Charitable_User_Management::prevent_wp_login()
 */
add_action('login_form_login', array( 'Charitable_User_Management', 'prevent_wp_login' ) );

/**
 * Redirect user from wp-login.php to charitable login page, upon failed auth,
 * if Hide Default WP Login Page is selected in the settings
 *
 * @see Charitable_User_Management::maybe_redirect_at_authenticate()
 */
add_filter( 'authenticate', array( 'Charitable_User_Management', 'maybe_redirect_at_authenticate') , 101, 3 );

/**
 * On the login page, look in the session for login error messages or notices
 * and display them if they are present.
 *
 * @see Charitable_User_Management::get_login_errors_from_session()
 * @see charitable_login_form_before
 */
add_action( 'charitable_login_form_before', array( 'Charitable_User_Management', 'get_login_errors_from_session' ) );

