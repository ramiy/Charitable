<?php
/**
 * Class that manages the hook functions for the forgot password form.
 *
 * @package     Charitable/User Management/User Management
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_User_Management' ) ) :

	/**
	 * Charitable_User_Management class
	 *
	 * @since       1.4.0
	 */
	class Charitable_User_Management {

		/**
		 * The class instance.
		 *
		 * @var 	Charitable_User_Management
		 * @access 	private
		 * @static
		 * @since 	1.4.0
		 */
		private static $instance;

		/**
		 * Returns and/or create the single instance of this class.
		 *
		 * @return  Charitable_User_Management
		 * @access  public
		 * @since   1.4.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Charitable_User_Management();
			}

			return self::$instance;
		}

		/**
		 * Set up the class.
		 *
		 * @access  private
		 * @since   1.4.0
		 */
		private function __construct() {
		}

		/**
		 * Check whether we have clicked on a password reset link.
		 *
		 * If so, redirect to the password reset page without the query string.
		 *
		 * @return  false|void False if no redirect takes place.
		 * @access  public
		 * @since   1.4.0
		 */
		public function maybe_redirect_to_password_reset() {

			if ( ! charitable_is_page( 'reset_password_page' ) ) {
				return false;
			}

			if ( ! isset( $_GET['key'] ) || ! isset( $_GET['login'] ) ) {
				return false;
			}

			$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );

			$this->set_reset_cookie( $value );

			wp_safe_redirect( charitable_get_permalink( 'reset_password_page' ) );

			exit();

		}

		/**
		 * Set the password reset cookie.
		 *
		 * This is based on the WC_Shortcode_My_Account::set_reset_password_cookie()
		 * method in WooCommerce, which in turn is based on the core implementation
		 * in wp-login.php.
		 *
		 * @param 	string $value
		 * @return  void
		 * @access  public
		 * @since   1.4.0
		 */
		public function set_reset_cookie( $value = '' ) {

			$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
			$rp_path   = current( explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) ) );

			if ( $value ) {
				setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			} else {
				setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			}

		}

		/**
		 * Hides WP Admin bar if the user is not allowed to see it.
		 *
		 * Uses the builtin show_admin_bar function.
		 *
		 * @see 	show_admin_bar()
		 *
		 * @access 	public
		 * @static
		 * @since 	1.4.0
		 */
		public function maybe_remove_admin_bar() {

			/**
			 * To enable the admin bar for users without admin bar access,
			 * you can use this one-liner:
			 *
			 * add_filter( 'charitable_disable_admin_bar', '__return_true' );
			 */
			if ( ! apply_filters( 'charitable_disable_admin_bar', true ) ) {
				return;
			}

			if ( ! $this->user_has_admin_access() ) {
				show_admin_bar( false );
			}

		}

		/**
		 * Redirects the user away from /wp-admin if they are not authorized to access it.
		 *
		 * @access 	public
		 * @since 	1.4.0
		 */
		public function maybe_redirect_away_from_admin() {

			/* Leave AJAX requests alone. */
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			}

			/**
			 * To enable admin access for users without admin access,
			 * you can use this one-liner:
			 *
			 * add_filter( 'charitable_disable_admin_access', '__return_true' );
			 */
			if ( ! apply_filters( 'charitable_disable_admin_access', true ) ) {
				return;
			}

			if ( $this->user_has_admin_access() ) {
				return;
			}

			/**
			 * Specify a custom URL that users should be redirected to.
			 *
			 * @hook 	charitable_admin_redirect_url
			 */
			$redirect_url = apply_filters( 'charitable_admin_redirect_url', false );

			if ( ! $redirect_url ) {

				$redirect_url = charitable_get_permalink( 'profile_page' );

				if ( ! $redirect_url ) {
					$redirect_url = home_url();
				}
			}

			wp_safe_redirect( $redirect_url );

			exit();

		}

		/**
		 * Redirect the user to the Charitable login page.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.4.0
		 */
		public function redirect_to_charitable_login() {

			wp_safe_redirect( charitable_get_permalink( 'login_page' ) );
			exit();

		}

		/**
		 * Check whether the user has admin access.
		 *
		 * @return  boolean
		 * @access  private
		 * @since   1.4.0
		 */
		private function user_has_admin_access() {

			if ( ! is_user_logged_in() ) {
				return false;
			}

			$ret = current_user_can( 'edit_posts' ) || current_user_can( 'manage_charitable_settings' );

			return apply_filters( 'charitable_user_has_admin_access', $ret );

		}

		/**
		 * Returns true if the current user is allowed to view wp admin
		 *
		 * @access private
		 * @static
		 * @since 1.4.0
		 */
		// private static function current_user_should_see_wp_admin() {
		// 	$all_roles = array_keys( self::editable_roles() );
		// 	$from_settings = array();
		// 	if ( is_array( get_option( 'charitable_settings' ) ) && array_key_exists( 'view_wp_admin', get_option( 'charitable_settings' ) ) && $view_setting = get_option( 'charitable_settings' )[ 'view_wp_admin_bar' ] ) {
		// 		$from_settings = $view_setting;
		// 	}
		// 	$ok_roles = array_merge( $from_settings, array( 'administrator' ) );
		// 	$not_ok_roles = array_diff( $all_roles, $ok_roles );
		// 	return self::user_has_only_ok_roles( $ok_roles, $not_ok_roles );
		// }

		/**
		 * Returns all editable roles from default WordPress function
		 *
		 * @access private
		 * @static
		 * @since 1.4.0
		 */
		// private static function editable_roles() {
		// 	if ( ! function_exists( 'get_editable_roles' ) ) {
		// 		require_once( ABSPATH . '/wp-admin/includes/user.php' );
		// 	}
		// 	return get_editable_roles();
		// }

		/**
		 * Returns true if the current user is allowed to view wp admin bar
		 *
		 * @access private
		 * @static
		 * @since 1.4.0
		 */
		// private static function current_user_should_see_wp_admin_bar() {
		// 	$all_roles = array_keys( self::editable_roles() );
		// 	$from_settings = array();
		// 	if ( is_array( get_option( 'charitable_settings' ) ) && array_key_exists( 'view_wp_admin_bar', get_option( 'charitable_settings' ) ) && $view_setting = get_option( 'charitable_settings' )[ 'view_wp_admin_bar' ] ) {
		// 		$from_settings = $view_setting;
		// 	}
		// 	$ok_roles = array_merge( $from_settings, array( 'administrator' ) );
		// 	$not_ok_roles = array_diff( $all_roles, $ok_roles );
		// 	return self::user_has_only_ok_roles( $ok_roles, $not_ok_roles );
		// }

		/**
		 * Returns true if user has at least one OK role and zero not OK roles
		 *
		 * @access private
		 * @static
		 * @since 1.4.0
		 * @global WP_Roles $wp_roles
		 */
		// private static function user_has_only_ok_roles( $ok_roles, $not_ok_roles ) {
		// 	global $wp_roles;

		// 	$roles = wp_get_current_user()->roles;

		// 	if ( in_array( 'administrator', $roles ) ) {
		// 		return true;
		// 	}

		// 	$user_ok_roles     = array_intersect( $ok_roles, $roles );
		// 	$user_not_ok_roles = array_intersect( $not_ok_roles, $roles );

		// 	/* Return true if the user has one of the approved roles and
		// 	 * none of the not-approved roles. */
		// 	return ! empty( $user_ok_roles ) && empty( $user_not_ok_roles );
		// }

		/**
		 * Check for a redirect_to query arg in $_REQUEST
		 *
		 * If query ?redirect_to= query arg is present in $_REQUEST, append it to $url
		 *
		 * @access private
		 * @static
		 * @since 1.4.0
		 */
		// private static function maybe_add_redirect( $url ) {
		// 	$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
		// 	if ( ! empty( $redirect_to ) ) {
		// 		$url = add_query_arg( 'redirect_to', $redirect_to, $url );
		// 	}
		// 	return $url;
		// }

		/**
		 * If user tries to access wp-login.php, redirect to charitable login page
		 *
		 * Do this if Hide Default WP Login Page is selected in the settings
		 *
		 * @access public
		 * @static
		 * @since 1.4.0
		 */
		// public static function prevent_wp_login() {
		// 	if ( get_option( 'charitable_settings' )[ 'hide_wp_login' ] ) {
		// 		if( $_SERVER['REQUEST_METHOD'] == 'GET') {
		// 			// get charitable login url
		// 			$login_url = esc_url( charitable_get_login_page_permalink( null ) );

		// 			// if login url is not default wp-login.php, redirect all GET requests to
		// 			// wp-login.php to custom login page
		// 			if ( $login_url != wp_login_url() ) {
		// 				$login_url = self::maybe_add_redirect( $login_url );
		// 				wp_redirect( $login_url );
		// 				exit;
		// 			}
		// 		}
		// 	}
		// }

		/**
		 * Redirect user to custom login page upon failed auth
		 *
		 * Do this if Hide Default WP Login Page is selected in the settings. Also
		 * Add error messages to the session so they can be displayed on custom
		 * login page
		 *
		 * @access public
		 * @static
		 * @since 1.4.0
		 */
		// public static function maybe_redirect_at_authenticate( $user_or_error, $username, $password ) {
		// 	if ( get_option( 'charitable_settings' )[ 'hide_wp_login' ] ) {
		// 		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		// 			if ( is_wp_error( $user_or_error ) ) {
		// 				charitable_get_session()->set( 'login_errors', $user_or_error->get_error_messages() );
		// 				$login_url = esc_url( charitable_get_login_page_permalink( null ) );
		// 				if ( $login_url != wp_login_url() ) {
		// 					$login_url = self::maybe_add_redirect( $login_url );
		// 					wp_redirect( $login_url );
		// 					exit;
		// 				}
		// 			}
		// 		}
		// 	}

		// 	return $user_or_error;
		// }

		/**
		 * Look in the session for login error messages or notices and
		 * display them if they are present.
		 *
		 * @access public
		 * @static
		 * @since 1.4.0
		 */
		// public static function get_login_errors_from_session() {
		// 	if ( $errors = charitable_get_session()->get( 'login_errors' ) ) {
		// 		charitable_get_session()->set( 'login_errors', null );
		// 		charitable_template( 'form-fields/errors.php', array(
		// 			'errors' => $errors,
		// 			'form' => 'bogus',
		// 		) );
		// 	}
		// }
	}

endif;
