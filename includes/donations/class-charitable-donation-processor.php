<?php
/**
 * The class that is responsible for responding to donation events.
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Donation_Processor
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation_Processor' ) ) : 

/**
 * Charitable Donation Processor.
 *
 * @since       1.0.0
 */
class Charitable_Donation_Processor {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Donation_Processor|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * The campaign receiving a donation. 
     *
     * @var     Charitable_Campaign|false
     * @access  protected
     */
    protected $campaign;

    /**
     * The donation data. 
     *
     * @var     mixed[]
     * @access  protected
     */
    protected $donation_data;

    /**
     * The campaign donations array.  
     *
     * @var     array
     * @access  protected
     */
    protected $campaign_donations_data;

    /**
     * The donor ID for the current donation. 
     *
     * @var     int
     * @access  protected
     */
    protected $donor_id;

    /**
     * The donoration ID for the current donation. 
     *
     * @var     int
     * @access  protected
     */
    protected $donation_id;

    /**
     * Create class object. A protected constructor, so this is used in a singleton context. 
     * 
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {
        $this->campaign = charitable_get_current_campaign();
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Donation_Processor
     * @access  public
     * @since   1.0.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Donation_Processor();
        }

        return self::$instance;
    }
    
    /**
     * Destroy the Charitable_Donation_Processor instance.
     *
     * This is primarily useful for testing purposes, as it allows you to 
     * create multiple donations in a single request. 
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.3.0
     */
    public static function destroy() {
        self::$instance = null;
    }

    /**
     * Return the current campaign. 
     *
     * @return  Charitable_Campaign|false False if no campaign is set. Campaign object otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function get_campaign() {
        return $this->campaign;
    }

    /**
     * Executed when a user first clicks the Donate button on a campaign. 
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function add_donation_to_session() {
        $processor = self::get_instance();

        if ( ! $processor->get_campaign() ) {
            return;
        }       

        /* Save the donation in the session */
        charitable_get_session()->add_donation( $processor->get_campaign()->ID, 0 );
        
        $donations_url = charitable_get_permalink( 'campaign_donation_page', array( 'campaign_id' => $processor->get_campaign()->ID ) );
        
        wp_redirect( $donations_url );

        die();
    }

    /**
     * Process the donation. 
     *
     * This is used by all donation form submissions, AJAX or not.  
     *
     * @return  mixed
     * @access  public
     * @since   1.3.0
     */
    public function process_donation( ) {
        $processor = self::get_instance();
        $campaign = $processor->get_campaign();

        if ( ! $campaign ) {
            return;
        }

        /* Validate the form submission and retrieve the values. */
        $form = $campaign->get_donation_form();

        /**
         * @hook charitable_before_process_donation_form
         */
        do_action( 'charitable_before_process_donation_form', $processor, $form );

        if ( ! $form->validate_submission() ) {
            return false;
        }

        $values = $form->get_donation_values();
        
        $gateway = $values[ 'gateway' ];

        /* Validate the gateway values */    
        if ( ! apply_filters( 'charitable_validate_donation_form_submission_gateway', true, $gateway, $values ) ) {
            return false;
        }
        
        $this->donation_id = $processor->save_donation( $values );

        /**
         * We check whether the gateway is compatible with version 1.3, since Charitable 1.3
         * change the hook into a filter (instead of an action).
         */        
        if ( $this->gateway_is_130_compatible( $gateway ) ) { 

            /**
             * Fire a hook for payment gateways to process the donation.
             *
             * - TRUE :  If the donation was processed successfully and the user should 
             *           be redirected to the donation receipt.
             * - FALSE : If the donation could not be processed successfully and the user
             *           should be redirected back to the donation form.
             * - ARRAY : If the user needs to be redirected somewhere other than the donation
             *           receipt or donation form. Array should contain two values: 
             *           `redirect` : The url to be redirected to.
             *           `safe` : Whether to use wp_safe_redirect. If unset, will default to true.
             *
             * @hook charitable_process_donation_$gateway
             */                        
            return apply_filters( 'charitable_process_donation_' . $gateway, true, $this->donation_id, $processor );            
        }
        else {
            /**
             * A fallback for payment gateways that have not been updated. 
             */
            do_action( 'charitable_process_donation_' . $gateway, $this->donation_id, $processor );

            /**
             * If we get this far, forward the user through to the receipt page.
             */
            wp_safe_redirect( charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $this->donation_id ) ) );
            
            die();
        }      
    }

    /**
     * Save a donation submitted through a page reload (i.e. not AJAX).
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_donation_form_submission() {
        $processor = self::get_instance();

        $result = $processor->process_donation();

        $processor->redirect_after_gateway_processing( $result );
    }

    /**
     * Add a donation with AJAX. 
     *
     * @return  json
     * @access  public
     * @static
     * @since   1.3.0
     */
    public static function ajax_process_donation_form_submission() {  
        if ( ! isset( $_POST[ 'campaign_id' ] ) ) {
            wp_send_json_error( new WP_Error( 'missing_campaign_id', __( 'Campaign ID was not found. Unable to create donation.', 'charitable' ) ) );
        }

        $processor = self::get_instance();

        $result = $processor->process_donation();

        if ( $result ) {
            $response = array(
                'success' => true,
                'redirect_to' => $processor->get_redirection_after_gateway_processing( $result )
            );
        }
        else {
            $errors = charitable_get_notices()->get_errors();

            if ( empty( $errors ) ) {
                $errors = array( __( 'Unable to process donation.', 'charitable' ) );
            }

            ob_start();
            
            charitable_template( 'form-fields/errors.php', array(
                'errors' => $errors
            ) );

            $errors = ob_get_clean(); 
            
            $response = array(
                'success' => false,
                'errors' => $errors
            );
        }        

        wp_send_json( $response );

        exit();
    }

    /**
     * Save a donation.
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function make_donation_streamlined() {
        $processor = self::get_instance();
        $campaign = $processor->get_campaign();

        if ( ! $campaign ) {
            return;
        }

        /* Validate the form submission and retrieve the values. */
        $form = new Charitable_Donation_Amount_Form( $campaign );

        /**
         * @hook charitable_before_process_donation_amount_form
         */
        do_action( 'charitable_before_process_donation_amount_form', $processor, $form );        

        if ( ! $form->validate_submission() ) {
            return;
        }

        $submitted = $form->get_donation_values();

        charitable_get_session()->add_donation( $submitted[ 'campaign_id' ], $submitted[ 'amount' ] );
        
        /**
         * @hook charitable_after_process_donation_amount_form
         */
        do_action( 'charitable_after_process_donation_amount_form', $processor, $submitted );

        /**
         * If we get this far, forward the user through to the donation page.
         */
        wp_safe_redirect( charitable_get_permalink( 'campaign_donation_page', array( 'campaign_id' => $submitted[ 'campaign_id' ] ) ) );
        die();
    } 

    /**
     * Inserts a new donation.
     *
     * This method is designed to be completely form agnostic. 
     *
     * We use this when integrating third-party systems like Easy Digital Downloads and 
     * WooCommerce. 
     *
     * @param   mixed[] $values
     * @return  int $donation_id    Returns 0 in case of failure. Positive donation ID otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function save_donation( array $values ) {
        /**
         * @hook charitable_donation_values
         */        
        $this->donation_data = apply_filters( 'charitable_donation_values', $values );

        if ( ! $this->get_campaign_donations_data() ) {
            _doing_it_wrong( __METHOD__, 'A donation cannot be inserted without an array of campaigns being donated to.', '1.0.0' );
            return 0;
        }

        if ( ! $this->is_valid_user_data() ) {
            _doing_it_wrong( __METHOD__, 'A donation cannot be inserted without valid user data.', '1.0.0' );
            return 0;
        }

        /**
         * @hook charitable_before_save_donation
         */
        do_action( 'charitable_before_save_donation', $this );

        $donation_id = wp_insert_post( $this->parse_donation_data() );

        $this->set_donation_key();

        if ( is_wp_error( $donation_id ) ) {
            charitable_get_notices()->add_errors_from_wp_error( $donation_id );
            return 0;
        }

        if ( 0 == $donation_id ) {
            charitable_get_notices()->add_error( __( 'We were unable to save the donation. Please try again.', 'charitable' ) );
            return 0;
        }
        
        $this->save_campaign_donations( $donation_id );

        $this->save_donation_meta( $donation_id );             

        $this->update_donation_log( $donation_id, __( 'Donation created.', 'charitable' ) );

        /**
         * If we're not in the admin or we're doing AJAX, write to the session.
         */
        if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            charitable_get_session()->add_donation_key( $this->get_donation_data_value( 'donation_key' ) );            
        }

        /**
         * @hook charitable_after_save_donation
         */
        do_action( 'charitable_after_save_donation', $donation_id, $this );
    
        return $donation_id;
    }

    /**
     * Inserts the campaign donations into the campaign_donations table. 
     *
     * @param   int $donation_id
     * @return  int The number of donations inserted. 
     * @access  public
     * @since   1.0.0
     */
    public function save_campaign_donations( $donation_id ) {        
        $campaigns = $this->get_campaign_donations_data();

        foreach ( $campaigns as $campaign ) {
            $campaign[ 'donor_id' ] = $this->get_donor_id();
            $campaign[ 'donation_id' ] = $donation_id;
        
            $campaign_donation_id = charitable_get_table( 'campaign_donations' )->insert( $campaign );

            if ( 0 == $campaign_donation_id ) {
                return 0;
            }
        }

        return count( $campaigns );
    }

    /**
     * Save the meta for the donation.  
     *
     * @param   int $donation_id
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function save_donation_meta( $donation_id ) {
        $meta = array(
            'donation_gateway'  => $this->get_donation_data_value( 'gateway' ), 
            'donor'             => $this->get_donation_data_value( 'user' ), 
            'test_mode'         => charitable_get_option( 'test_mode', 0 ), 
            'donation_key'      => $this->get_donation_data_value( 'donation_key' )
        );

        if ( $this->get_donation_data_value( 'meta' ) ) {
            $meta = array_merge( $meta, $this->get_donation_data_value( 'meta' ) );
        }

        $meta = apply_filters( 'charitable_donation_meta', $meta, $donation_id, $this );

        foreach ( $meta as $meta_key => $value ) {  
            $value = apply_filters( 'charitable_sanitize_donation_meta', $value, $meta_key );
            update_post_meta( $donation_id, $meta_key, $value );
        }        
    }

    /**
     * Add a message to the donation log. 
     *
     * @param   string      $message
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function update_donation_log( $donation_id, $message ) {
        $log = Charitable_Donation::get_donation_log( $donation_id );

        $log[] = array( 
            'time'      => time(), 
            'message'   => $message
        );

        update_post_meta( $donation_id, '_donation_log', $log );
    }

    /**
     * Returns the submitted donation data. 
     *
     * @return  mixed[]
     * @access  public
     * @since   1.0.0
     */
    public function get_donation_data() {
        return $this->donation_data;
    }

    /**
     * Return the submitted value for a particular key.
     *
     * @param   string $key     The key to search for.
     * @param   mixed $default  Fallback value to return if the data is not set.
     * @return  mixed
     * @access  public
     * @since   1.0.0
     */
    public function get_donation_data_value( $key, $default = false ) {
        $data = $this->get_donation_data();
        return isset( $data[ $key ] ) ? $data[ $key ] : $default;
    }

    /**
     * Returns the campaign donations array, or false if the data is invalid. 
     *
     * @return  string[]|false
     * @access  public
     * @since   1.0.0
     */
    public function get_campaign_donations_data() {
        if ( ! isset( $this->campaign_donations_data ) ) {

            if ( ! is_array( $this->get_donation_data_value( 'campaigns' ) ) ) {
                return false;
            }
            
            $this->campaign_donations_data = array();

            foreach ( $this->get_donation_data_value( 'campaigns' ) as $campaign ) {

                /* If the amount or campaign_id are missing, this donation won't work. */
                if ( ! isset( $campaign[ 'campaign_id' ] ) || ! isset( $campaign[ 'amount' ] ) ) {
                    return false;
                }

                if ( ! isset( $campaign[ 'campaign_name' ] ) ) {
                    $campaign[ 'campaign_name' ] = get_the_title( $campaign[ 'campaign_id' ] );
                }

                $this->campaign_donations_data[] = $campaign;
            }
        }
        
        return $this->campaign_donations_data;
    }

    /**
     * Returns the donor_id for the current donation. 
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_donor_id() {
        if ( ! isset( $this->donor_id ) ) {
            $this->donor_id = $this->get_donation_data_value( 'donor_id', 0 );

            if ( $this->donor_id ) {
                return $this->donor_id;
            }

            $user_id = $this->get_donation_data_value( 'user_id', get_current_user_id() );
            $user_data = $this->get_donation_data_value( 'user', array() );

            if ( $user_id ) {
                $this->donor_id = charitable_get_table( 'donors' )->get_donor_id( $user_id );
            }    
            elseif ( isset( $user_data[ 'email' ] ) ) {
                $this->donor_id = charitable_get_table( 'donors' )->get_donor_id_by_email( $user_data[ 'email' ] );
            }

            /* If we still do not have a donor ID, it means that this is a first-time donor */
            if ( 0 == $this->donor_id ) {
                $user = new Charitable_User( $user_id );
                $this->donor_id = $user->add_donor( $user_data );
            }
        }        
        
        return $this->donor_id;
    }

    /**
     * Return the IPN url.
     *
     * IPNs in Charitable are structured in this way: charitable-listener=gateway
     *
     * @param   string $gateway
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_ipn_url( $gateway ) {
        return add_query_arg( 'charitable-listener', $gateway, home_url( 'index.php' ) );
    }

    /**
     * Checks for calls to our IPN. 
     *
     * This method is called on the init hook.
     *
     * IPNs in Charitable are structured in this way: charitable-listener=gateway
     *
     * @return  boolean True if this is a call to our IPN. False otherwise.
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function ipn_listener() {
        if ( isset( $_GET[ 'charitable-listener' ] ) ) {

            $gateway = $_GET[ 'charitable-listener' ];
            do_action( 'charitable_process_ipn_' . $gateway );
            return true;
        }

        return false;
    }

    /**
     * Redirect the user after the gateway has processed the donation. 
     *
     * @uses    Charitable_Donation_Processor::get_redirection_after_gateway_processing()
     * @param   mixed $gateway_processing
     * @return  void
     * @access  private
     * @since   1.3.0
     */
    private function redirect_after_gateway_processing( $gateway_processing ) {
        $redirect_url = $this->get_redirection_after_gateway_processing( $gateway_processing );

        /** 
         * If the gateway processing failed, add the error notices to the session. 
         */
        if ( false == $gateway_processing ) {
            charitable_get_session()->add_notices();
        }

        /**
         * Set the redirect status to use.
         */
        $status = isset( $gateway_processing[ 'status' ] ) ? $gateway_processing[ 'status' ] : 302;

        /** 
         * If the gateway processing returned an array with a directive to NOT
         * use wp_safe_redirect, use wp_redirect instead.
         */
        if ( isset( $gateway_processing[ 'safe' ] ) && false == $gateway_processing[ 'safe' ] ) {

            wp_redirect( $redirect_url, $status );
            die();

        }        

        wp_safe_redirect( $redirect_url, $status );

        die();
    }

    /**
     * Return the URL that the donor should be redirected to.
     *
     * @param   mixed $gateway_processing
     * @param   int $donation_id
     * @return  string
     * @access  private
     * @since   1.3.0
     */
    private function get_redirection_after_gateway_processing( $gateway_processing ) {
        if ( false == $gateway_processing ) {
            
            $redirect_url = esc_url( add_query_arg( array( 'donation_id' => $this->donation_id ), wp_get_referer() ) );            

        }
        elseif ( is_array( $gateway_processing ) && isset( $gateway_processing[ 'redirect' ] ) ) {

            $redirect_url = $gateway_processing[ 'redirect' ];

        }
        else {

            /* Fall back to returning the donation receipt URL. */
            $redirect_url = charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $this->donation_id ) );

        }
        
        return $redirect_url;
    }

    /**
     * Validate user data passed to insert. 
     *
     * @return  boolean
     * @access  protected
     * @since   1.0.0
     */
    protected function is_valid_user_data() {
        $ret = $this->get_donation_data_value( 'user_id' ) || $this->get_donation_data_value( 'donor_id' );

        if ( ! $ret ) {
            $user = $this->get_donation_data_value( 'user' );
            $ret = isset( $user[ 'email' ] );
        }

        return $ret;
    }

    /**
     * Parse the donation data, based on the passed $values array. 
     *
     * @return  array
     * @access  protected
     * @since   1.0.0
     */
    protected function parse_donation_data() {        
        $core_values = array(
            'post_type'     => Charitable::DONATION_POST_TYPE, 
            'post_author'   => $this->get_donation_data_value( 'user_id', get_current_user_id() ), 
            'post_status'   => $this->get_donation_status(),
            'post_content'  => $this->get_donation_data_value( 'note', '' ), 
            'post_parent'   => $this->get_donation_data_value( 'donation_plan', 0 ),
            'post_date_gmt' => $this->get_donation_data_value( 'date_gmt', current_time( 'mysql', true ) ),
            'post_title'    => sprintf( '%s &ndash; %s', $this->get_donor_name(), $this->get_campaign_names() )
        );

        $core_values[ 'post_date' ] = get_date_from_gmt( $core_values[ 'post_date_gmt' ] );

        return apply_filters( 'charitable_donation_values_core', $core_values, $this );
    }

    /**
     * Returns the donation status. Defaults to charitable-pending.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_donation_status() {
        $status = $this->get_donation_data_value( 'status', 'charitable-pending' );

        if ( ! Charitable_Donation::is_valid_donation_status( $status ) ) {
            $status = 'charitable-pending';
        }

        return $status;
    }

    /**
     * Returns the name of the donor. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_donor_name() {
        $user = new WP_User( $this->get_donation_data_value( 'user_id', 0 ) );
        $user_data = $this->get_donation_data_value( 'user' );
        $first_name = isset( $user_data[ 'first_name' ] ) ? $user_data[ 'first_name' ] : $user->get( 'first_name' );
        $last_name = isset( $user_data[ 'last_name' ] ) ? $user_data[ 'last_name' ] : $user->get( 'last_name' );
        return trim( sprintf( '%s %s', $first_name, $last_name ) );
    }

    /**
     * Returns a comma separated list of the campaigns that are being donated to. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_campaign_names() {
        $campaigns = wp_list_pluck( $this->get_campaign_donations_data(), 'campaign_name' );
        return implode( ', ', $campaigns );
    }

    /**
     * Set a unique key for the donation. 
     *
     * @return  void
     * @access  protected
     * @since   1.0.0
     */
    protected function set_donation_key() {
        $this->donation_data[ 'donation_key' ] = strtolower( md5( uniqid() ) );
    }

    /**
     * Checks whether the given gateway has been updated for compatibility with 1.3.
     *
     * @param   string $gateway
     * @return  boolean
     * @access  private
     * @since   1.3.0
     */
    private function gateway_is_130_compatible( $gateway ) {
        return Charitable_Gateways::get_instance()->get_gateway_object( $gateway )->supports( '1.3.0' );
    }
}

endif; // End class_exists check.