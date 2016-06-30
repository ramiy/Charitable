<?php
/**
 * Class that manages the display and processing of the forgot password form.
 *
 * @package     Charitable/Classes/Charitable_Forgot_Password_Form
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Forgot_Password_Form' ) && class_exists( 'Charitable_Form' ) ):

  /**
   * Charitable_Forgot_Password_Form
   *
   * @since       1.4.0
   */
  class Charitable_Forgot_Password_Form extends Charitable_Form {

    /**
     * Shortcode parameters.
     *
     * @var     array
     * @access  protected
     */
    protected $shortcode_args;

    /**
     * Create class object.
     *
     * @param   array       $args       User-defined shortcode attributes.
     * @access  public
     * @since   1.4.0
     */
    public function __construct( $args = array() ) {
      $this->id = uniqid();
      $this->shortcode_args = $args;
      $this->attach_hooks_and_filters();
    }

    /**
     * Forgot password fields to be displayed.
     *
     * @return  array
     * @access  public
     * @since   1.4.0
     */
    public function get_fields() {
      $fields = apply_filters( 'charitable_forgot_password_fields', array(
        'user_login' => array(
          'label'               => __( 'Email Address', 'charitable' ),
          'type'                => 'text',
          'required'            => true,
          'priority'            => 10,
        ),
      ) );

      uasort( $fields, 'charitable_priority_sort' );
      return $fields;
    }
  }

endif;
