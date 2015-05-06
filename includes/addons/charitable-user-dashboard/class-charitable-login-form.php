<?php
/**
 * Class that manages the display and processing of the login form.
 *
 * @package     Charitable/Classes/Charitable_Login_Form
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Login_Form' ) ) : 

/**
 * Charitable_Login_Form
 *
 * @since       1.0.0
 */
class Charitable_Login_Form {

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
     * @since   1.0.0
     */
    public function __construct( $args = array() ) {    
        $this->shortcode_args = $args;      
    }

    /**
     * Return arguments to pass to wp_login_form.  
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function get_login_form_args() {
        if ( isset( $_GET[ 'redirect_to' ] ) ) {
            $redirect = $_GET[ 'redirect_to' ];
        }
        elseif ( charitable_get_permalink( 'profile_page' ) ) {
            $redirect = charitable_get_permalink( 'profile_page' );
        }
        else {
            $redirect = site_url();
        }
        
        return apply_filters( 'charitable_login_form_args', array(
            'redirect' => esc_url( $redirect )
        ) );
    }
}

endif; // End class_exists check