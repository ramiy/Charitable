<?php
/**
 * Charitable User Management Settings UI.
 *
 * @package     Charitable/Classes/Charitable_User_Management_Settings
 * @version     1.0.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_User_Management_Settings' ) ) :

  /**
   * Charitable_User_Management_Settings
   *
   * @final
   * @since      1.0.0
   */
  final class Charitable_User_Management_Settings {

    /**
     * The single instance of this class.
     *
     * @var     Charitable_User_Management_Settings|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Create object instance.
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {
    }

    /**
     * Returns and/or create the single instance of this class.
     *
     * @return  Charitable_User_Management_Settings
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new Charitable_User_Management_Settings();
      }

      return self::$instance;
    }

    /**
     * Add the user management tab settings fields.
     *
     * @param   array[] $fields
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_user_management_fields( $fields = array() ) {
      if ( ! charitable_is_settings_view( 'user_management' ) ) {
        return array();
      }
      $user_management_fields = array(
        'section'               => array(
          'title'             => '',
          'type'              => 'hidden',
          'priority'          => 10000,
          'value'             => 'user_management'
        ),
        'section_pages'         => array(
          'title'             => __( 'Pages', 'charitable' ),
          'type'              => 'heading',
          'priority'          => 30
        ),
        'login_page'            => array(
          'title'             => __( 'Login Page', 'charitable' ),
          'type'              => 'select',
          'priority'          => 34,
          'default'           => 'wp',
          'options'           => array(
            'wp'            => __( 'Use WordPress Login', 'charitable' ),
            'pages'         => array(
              'options'   => charitable_get_admin_settings()->get_pages(),
              'label'     => __( 'Choose a Static Page', 'charitable' )
            )
          ),
          'help'              => __( 'Allow users to login via the normal WordPress login page or via a static page. The static page should contain the <code>[charitable_login]</code> shortcode.', 'charitable' )

        ),
        'forgot_password_page'  => array(
          'title'             => 'Forgot Password Page',
          'type'              => 'select',
          'priority'          => 35,
          'default'           => 'wp',
          'options'           => array(
            'wp'              => 'Use WordPress Forgot Password Page',
            'pages'           => array(
              'options'       => charitable_get_admin_settings()->get_pages(),
              'label'         => __( 'Choose a Static Page', 'charitable' ),
            ),
          ),
          'help'              => 'Allow users to reset their password via the normal WordPress reset password page or via a static page. The static page should contain the <code>[charitable_forgot_password]</code> shortcode.'
        ),
        'reset_password_page'  => array(
          'title'             => 'Reset Password Page',
          'type'              => 'select',
          'priority'          => 35.5,
          'default'           => 'wp',
          'options'           => array(
            'wp'              => 'Use WordPress Reset Password Page',
            'pages'           => array(
              'options'       => charitable_get_admin_settings()->get_pages(),
              'label'         => __( 'Choose a Static Page', 'charitable' ),
            ),
          ),
          'help'              => 'Allow users to enter their new password via the normal WordPress reset password page or via a static page. The static page should contain the <code>[charitable_reset_password]</code> shortcode.'
        ),
        'registration_page' => array(
          'title'             => __( 'Registration Page', 'charitable' ),
          'type'              => 'select',
          'priority'          => 36,
          'default'           => 'wp',
          'options'           => array(
            'wp'            => __( 'Use WordPress Registration Page', 'charitable' ),
            'pages'         => array(
              'options'   => charitable_get_admin_settings()->get_pages(),
              'label'     => __( 'Choose a Static Page', 'charitable' )
            )
          ),
          'help'              => __( 'Allow users to register via the default WordPress login or via a static page. The static page should contain the <code>[charitable_registration]</code> shortcode.', 'charitable' )
        ),
        'section_wp_admin' => array(
          'title'             => __( 'WP Admin', 'charitable' ),
          'type'              => 'heading',
          'priority'          => 38
        ),
        'view_wp_admin_bar' => array(
          'title'    => __( 'View WP Admin Bar', 'charitable' ),
          'type'     => 'multi-checkbox',
          'priority' => 40,
          'default'  => charitable_get_admin_settings()->get_editable_roles(),
          'options'  => charitable_get_admin_settings()->get_editable_roles(),
          'help'     => __( 'Selected roles will see the WP Admin bar when logged in. Administrators can <em>always</em> see WP Admin bar.', 'charitable' ),
        ),
        'view_wp_admin' => array(
          'title'    => __( 'View WP Admin', 'charitable' ),
          'type'     => 'multi-checkbox',
          'priority' => 42,
          'default'  => charitable_get_admin_settings()->get_editable_roles(),
          'options'  => charitable_get_admin_settings()->get_editable_roles(),
          'help'     => __( 'Selected roles will view WP Admin by visiting /wp-admin. Other users will be given a 404 page when attempting to view WP Admin. Administrators can <em>always</em> see WP Admin.', 'charitable' ),
        ),
        '404_page' => array(
          'title'    => __( '404 Page', 'charitable' ),
          'type'     => 'text',
          'priority' => 44,
          'default'  => '/404.php',
          'help'     => __( 'Page to which the user is redirected if user attempts to visit /wp-admin and is not on the allowed user list', 'charitable' ),
        ),
        'hide_wp_login' => array(
          'title'    => __( 'Hide Default WP Login Page', 'charitable' ),
          'type'     => 'checkbox',
          'priority' => 46,
          'default'  => false,
          'help'     => __( 'Hide the default WordPress Login page entirely. Users will be automatically redirected to the pages selected above and will no longer be able to visit <code>/wp-login.php</code> directly.', 'charitable' ),
        )
      );

      $fields = array_merge( $fields, $user_management_fields );

      return $fields;
    }
  }

endif; // End class_exists check
