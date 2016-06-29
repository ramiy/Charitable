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
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_User_Management' ) ) :

  /**
   * Charitable_User_Management class
   *
   * @since       1.4.0
   */
  class Charitable_User_Management {


    /**
     * Check for a redirect_to query arg in $_REQUEST
     *
     * If query ?redirect_to= query arg is present in $_REQUEST, append it to
     * $url
     *
     * @access private
     * @static
     * @since 1.4.0
     */
    private static function maybe_add_redirect( $url ) {
      $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
      if ( ! empty( $redirect_to ) ) {
        $url = add_query_arg( 'redirect_to', $redirect_to, $url );
      }
      return $url;
    }

    /**
     * If user tries to access wp-login.php, redirect to charitable login page
     *
     * Do this if Hide Default WP Login Page is selected in the settings
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function prevent_wp_login() {
      if ( get_option( 'charitable_settings' )[ 'hide_wp_login' ] ) {
        if( $_SERVER['REQUEST_METHOD'] == 'GET') {
          // get charitable login url
          $login_url = esc_url( charitable_get_login_page_permalink( null ) );

          // if login url is not default wp-login.php, redirect all GET requests to
          // wp-login.php to custom login page
          if ( $login_url != wp_login_url() ) {
            $login_url = self::maybe_add_redirect( $login_url );
            wp_redirect( $login_url );
            exit;
          }
        }
      }
    }

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
    public static function maybe_redirect_at_authenticate( $user_or_error, $username, $password ) {
      if ( get_option( 'charitable_settings' )[ 'hide_wp_login' ] ) {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
          if ( is_wp_error( $user_or_error ) ) {
            charitable_get_session()->set( 'login_errors', $user_or_error->get_error_messages() );
            $login_url = esc_url( charitable_get_login_page_permalink( null ) );
            if ( $login_url != wp_login_url() ) {
              $login_url = self::maybe_add_redirect( $login_url );
              wp_redirect( $login_url );
              exit;
            }
          }
        }
      }

      return $user_or_error;
    }

    /**
     * Look in the session for login error messages or notices and
     * display them if they are present.
     *
     * @access public
     * @static
     * @since 1.4.0
     */
    public static function get_login_errors_from_session() {
      if ( $errors = charitable_get_session()->get( 'login_errors' ) ) {
        charitable_get_session()->set( 'login_errors', null );
        charitable_template( 'form-fields/errors.php', array(
          'errors' => $errors,
          'form' => 'bogus',
        ) );
      }
    }

    /**
     * Hides WP Admin bar if the user is not allowed to see it
     *
     * Uses the builtin show_admin_bar function
     *
     * @access pubilc
     * @static
     * @since 1.4.0
     * @see show_admin_bar()
     */
    public static function remove_admin_bar() {
      if ( ! self::current_user_should_see_wp_admin_bar() ) {
        show_admin_bar( false );
      }
    }

    /**
     * Returns a 404 response if unauthorized user attempts to view /wp-admin
     *
     * @access public
     * @static
     * @since 1.4.0
     * @global WP_Query $wp_query
     */
    public static function blockusers_init() {
      if ( ! self::current_user_should_see_wp_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        $four_oh_four_page = get_option( 'charitable_settings' )[ '404_page' ];
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        nocache_headers();
        wp_redirect( $four_oh_four_page );
        exit;
      }
    }

    /**
     * Returns true if the current user is allowed to view wp admin
     *
     * @access private
     * @static
     * @since 1.4.0
     */
    private static function current_user_should_see_wp_admin() {
      $all_roles = array_keys( self::editable_roles() );
      $ok_roles = array_merge( get_option( 'charitable_settings' )[ 'view_wp_admin' ], array( 'administrator' ) );
      $not_ok_roles = array_diff( $all_roles, $ok_roles );
      return self::user_has_only_ok_roles( $ok_roles, $not_ok_roles );
    }

    /**
     * Returns all editable roles from default WordPress function
     *
     * @access private
     * @static
     * @since 1.4.0
     */
    private static function editable_roles() {
      if ( ! function_exists( 'get_editable_roles' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/user.php' );
      }
      return get_editable_roles();
    }

    /**
     * Returns true if the current user is allowed to view wp admin bar
     *
     * @access private
     * @static
     * @since 1.4.0
     */
    private static function current_user_should_see_wp_admin_bar() {
      $all_roles = array_keys( self::editable_roles() );
      $ok_roles = array_merge( get_option( 'charitable_settings' )[ 'view_wp_admin_bar' ], array( 'administrator' ) );
      $not_ok_roles = array_diff( $all_roles, $ok_roles );
      return self::user_has_only_ok_roles( $ok_roles, $not_ok_roles );
    }

    /**
     * Returns true if user has at least one OK role and zero not OK roles
     *
     * @access private
     * @static
     * @since 1.4.0
     * @global WP_Roles $wp_roles
     */
    private static function user_has_only_ok_roles( $ok_roles, $not_ok_roles ) {
      global $wp_roles;
      $current_user = wp_get_current_user();
      $roles = $current_user->roles;

      return in_array( 'administrator', $roles ) || (
        !empty( array_intersect( $ok_roles, $roles ) ) && # user has one of the approved roles
        empty( array_intersect( $not_ok_roles, $roles ) ) # user has zero of the unapproved roles
      );
    }
  }

endif;
