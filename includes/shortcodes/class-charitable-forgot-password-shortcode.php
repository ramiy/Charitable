<?php
/**
 * Forgot Password shortcode class
 *
 * @version     1.0.0
 * @package     Charitable/Shortcodes/Forgot Password
 * @category    Class
 * @author      Rafe Colton
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Forgot_Password_Shortcode' ) ) :

  /**
   * Charitable_Forgot_Password_Shortcode class
   *
   * @since       1.4.0
   */
  class Charitable_Forgot_Password_Shortcode {

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
      $defaults = array( 'show_title' => true );

      $args = shortcode_atts( $defaults, $attrs, 'charitable_forgot_password' );

      ob_start();

      charitable_template( 'shortcodes/forgot-password.php', array(
        'form' => new Charitable_Forgot_Password_Form( $args ),
      ) );

      return apply_filters( 'charitable_forgot_password_shortcode', ob_get_clean() );
    }
  }

endif;
