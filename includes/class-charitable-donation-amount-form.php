<?php
/**
 * Donation amount form model class.
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Donation_Amount_Form
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Amount_Form' ) ) : 

/**
 * Charitable_Donation_Amount_Form
 *
 * @since       1.0.0
 */
class Charitable_Donation_Amount_Form extends Charitable_Donation_Form implements Charitable_Donation_Form_Interface {

    /** 
     * @var     Charitable_Campaign
     */
    protected $campaign;

    /**
     * @var     array
     */
    protected $form_fields;

    /**
     * @var     string
     */
    protected $nonce_action = 'charitable_donation_amount';

    /**
     * @var     string
     */
    protected $nonce_name = '_charitable_donation_amount_nonce';

    /**
     * Action to be executed upon form submission. 
     *
     * @var     string
     * @access  protected
     */
    protected $form_action = 'make_donation_streamlined';

    /**
     * Set up callbacks for actions and filters. 
     *
     * @return  void
     * @access  protected
     * @since   1.0.0
     */
    protected function attach_hooks_and_filters() {
        add_action( 'charitable_donation_form_amount', array( $this, 'add_hidden_fields' ), 1 ); 
        add_action( 'charitable_donation_form_amount', array( $this, 'enter_donation_amount' ) );
        add_action( 'charitable_donation_amount_form_submit', array( $this, 'redirect_after_submission' ), 10, 2 );

        do_action( 'charitable_donation_amount_form_start', $this );
    }

    /**
     * Returns the campaign associated with this donation form object. 
     *
     * @return  Charitable_Campaign
     * @access  public
     * @since   1.0.0
     */
    public function get_campaign() {
        return $this->campaign;
    }

    /**
     * Render the donation form. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function render() {
        $template = charitable_template( 'donation-form/form-donation-amount.php', array( 
            'form' => $this 
        ) );
    }

    /**
     * Save the submitted donation.
     *
     * @return  int|false       If successful, this returns the donation ID. If unsuccessful, returns false.
     * @access  public
     * @since   1.0.0
     */
    public function save_donation() {
        if ( ! $this->validate_nonce() ) {
            return false;
        }        

        /* Set the donation amount */
        $campaign_id = $_POST['campaign_id'];
        $amount = parent::get_donation_amount();

        if ( 0 == $amount && ! apply_filters( 'charitable_permit_empty_donations', false ) ) {
            charitable_get_notices()->add_error( __( 'No donation amount was set.', 'charitable' ) );
            return false;
        }

        /* Create or update the donation object in the session, with the current campaign ID. */
        charitable_get_session()->add_donation( $campaign_id, $amount );
        
        do_action( 'charitable_donation_amount_form_submit', $campaign_id, $amount );

        return true;        
    }

    /**
     * Redirect to payment form after submission. 
     *
     * @param   int     $campaign_id
     * @param   int     $amount
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function redirect_after_submission( $campaign_id, $amount ) {
        if ( defined('DOING_AJAX') && DOING_AJAX ) {
            return;
        }
        
        $redirect_url = charitable_get_permalink( 'campaign_donation_page', array( 'campaign_id' => $campaign_id ) );
        $redirect_url = apply_filters( 'charitable_donation_amount_form_redirect', $redirect_url, $campaign_id, $amount );
        
        wp_redirect( esc_url_raw( $redirect_url ) );

        die();
    }
}

endif; // End class_exists check