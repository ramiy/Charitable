<?php
/**
 * Reset Password shortcode class
 *
 * @version     1.0.0
 * @package     Charitable/Shortcodes/Reset Password
 * @category    Class
 * @author      Rafe Colton
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Reset_Password_Shortcode' ) ) :

  /**
   * Charitable_Reset_Password_Shortcode class
   *
   * @since       1.4.0
   */
  class Charitable_Reset_Password_Shortcode {

    /**
     * The callback method for the shortcode.
     *
     * This receives the user-defined attributes and passes the logic off to the class.
     *
     * @param   array   $atts   User-defined shortcode attributes.
     * @return  string
     * @access  public
     * @static
     * @since   1.4.0
     */
    public static function display( $attrs ) {
      $defaults = array();
      $args = shortcode_atts( $defaults, $attrs, 'charitable_reset_password' );

      $user_or_error = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
      Charitable_Reset_Password::check_error_and_redirect( $user_or_error );

      ob_start();

      charitable_template( 'shortcodes/reset-password.php', array(
        'form' => new Charitable_Reset_Password_Form( $args ),
        'login'      => $_REQUEST['login'],
        'key'        => $_REQUEST['key'],
      ) );

      return apply_filters( 'charitable_reset_password_shortcode', ob_get_clean() );
    }
  }

endif;
