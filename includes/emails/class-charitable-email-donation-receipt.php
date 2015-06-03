<?php
/**
 * Class that models the donation receipt email.
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Email_Donation_Receipt
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Email_Donation_Receipt' ) ) : 

/**
 * Donation Receipt Email 
 *
 * @since       1.0.0
 */
class Charitable_Email_Donation_Receipt extends Charitable_Email {

    /**
     * @var     string
     */
    CONST ID = 'donation_receipt';

    /**
     * @var     Charitable_Donation
     */
    protected $donation;

    /**
     * Instantiate the email class, defining its key values.
     *
     * @param   Charitable_Donation|null $donation 
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $donation = null ) {
        $this->name = apply_filters( 'charitable_email_donation_receipt_name', __( 'Donation Receipt', 'charitable' ) );    
        $this->donation = $donation;    

        add_filter( 'charitable_email_content_fields', array( $this, 'add_email_content_fields' ), 10, 2 );
        add_filter( 'charitable_email_preview_content_fields', array( $this, 'add_email_preview_content_fields' ), 10, 2 );
    }

    /**
     * Add our custom content fields.   
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_email_content_fields( $fields ) {            
        $fields[ 'donor_first_name' ] = array(
            'description'   => __( 'The first name of the donor', 'charitable' ), 
            'callback'      => array( $this, 'get_donor_first_name' )
        );
        $fields[ 'donor_full_name' ] = array(
            'description'   => __( 'The full name of the donor', 'charitable' ),
            'callback'      => array( $this, 'get_donor_full_name' )
        );

        return $fields;
    }

    /**
     * Return the first name of the donor. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_donor_first_name() {
        if ( ! $this->has_valid_donation() ) {
            return '';            
        }

        return $this->donation->get_donor()->first_name;
    }

    /**
     * Return the full name of the donor. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_donor_full_name() {
        if ( ! $this->has_valid_donation() ) {
            return '';            
        }

        return $this->donation->get_donor()->get_name();
    }

    /**
     * Add our custom content fields.   
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_email_preview_content_fields( $fields ) {
        $fields[ 'donor_first_name' ] = 'John';
        $fields[ 'donor_full_name' ] = 'John Deere';

        return $fields;
    }

    /**
     * Checks whether the email has a valid donation object set. 
     *
     * @return  boolean
     * @access  protected
     * @since   1.0.0
     */
    protected function has_valid_donation() {
        if ( is_null( $this->donation ) || ! is_a( $this, 'Charitable_Donation' ) ) {
            _doing_it_wrong( __METHOD__, __( 'You cannot send a donation receipt email without a donation!', 'charitable' ), '1.0.0' );
            return false;
        }

        return true;
    }

    /**
     * Return the default recipient for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_recipient() {
        if ( ! $this->has_valid_donation() ) {
            return '';
        }
        
        return $this->donation->get_donor()->user_email;
    }

    /**
     * Return the default subject line for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_subject() {
        return apply_filters( 'charitable_email_donation_receipt_default_subject', __( 'Thank you for your donation', 'charitable' ), $this );   
    }

    /**
     * Return the default headline for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_headline() {
        return apply_filters( 'charitable_email_donation_receipt_default_headline', __( 'Your Donation Receipt', 'charitable' ), $this );    
    }

    /**
     * Return the default body for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_body() {
        ob_start();
?>
        <p>Dear [charitable_email show=donor_first_name],</p>
        <p>Thank you so much for your generous donation.</p>
        <p>[charitable_email show=site_name]
<?php
        $body = ob_get_clean();

        return apply_filters( 'charitable_email_donation_receipt_default_body', $body, $this );
    }
}

endif; // End class_exists check