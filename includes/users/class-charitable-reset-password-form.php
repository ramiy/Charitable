<?php
/**
 * Class that manages the display and processing of the reset password form.
 *
 * @package     Charitable/Classes/Charitable_Reset_Password_Form
 * @version     1.4.0
 * @author      Rafe Colton
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Reset_Password_Form' ) && class_exists( 'Charitable_Form' ) ):

  /**
   * Charitable_Reset_Password_Form
   *
   * @since       1.4.0
   */
  class Charitable_Reset_Password_Form extends Charitable_Form {

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
     * Reset password fields to be displayed.
     *
     * @return  array
     * @access  public
     * @since   1.4.0
     */
    public function get_fields() {
      $fields = apply_filters( 'charitable_reset_password_fields', array(
        'pass1' => array(
          'label'               => __( 'New Password', 'charitable' ),
          'type'                => 'password',
          'required'            => true,
          'priority'            => 10,
          'attrs'               => array (
            'size'              => 20,
            'autocomplete'      => 'off',
          )
        ),
        'pass2' => array(
          'label'               => __( 'Repeat New Password', 'charitable' ),
          'type'                => 'password',
          'required'            => true,
          'priority'            => 11,
          'attrs'               => array (
            'size'              => 20,
            'autocomplete'      => 'off',
          )
        ),
      ) );

      uasort( $fields, 'charitable_priority_sort' );
      return $fields;
    }
  }

endif;
