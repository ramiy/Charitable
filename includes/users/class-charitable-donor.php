<?php
/**
 * Donor model. 
 *
 * @package     Charitable/Classes/Charitable_Donor
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Donor' ) ) : 

/**
 * Charitable_Donor
 *
 * @since       1.0.0
 */
class Charitable_Donor {

    /**
     * The donor ID. 
     *
     * @var     int
     * @access  protected
     */
    protected $donor_id;

    /**
     * The donation ID. 
     *
     * @var     int
     * @access  protected
     */
    protected $donation_id;    

    /**
     * User object. 
     *
     * @var     Charitable_User
     * @access  protected
     */
    protected $user;

    /**
     * Donation object. 
     *
     * @var     Charitable_Donation|null
     * @access  protected
     */
    protected $donation = null;

    /**
     * Donor meta. 
     *
     * @var     mixed[] 
     * @access  protected
     */
    protected $donor_meta;

    /**
     * Create class object.
     * 
     * @param   int $donor_id
     * @param   int $donation_id
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $donor_id, $donation_id = false ) {
        $this->donor_id = $donor_id;
        $this->donation_id = $donation_id;        
    }

    /**
     * Magic getter method. Looks for the specified key in as a property before using Charitable_User's __get method. 
     *
     * @return  mixed
     * @access  public
     * @since   1.0.0
     */
    public function __get( $key ) {
        if ( isset( $this->$key ) ) {
            return $this->$key;
        }

        return $this->get_user()->$key;
    }

    /**
     * A thin wrapper around the Charitable_User::get() method. 
     *
     * @param   string $key
     * @return  mixed
     * @access  public
     * @since   1.2.4
     */
    public function get( $key ) {
        return $this->get_user()->get( $key );
    }

    /**
     * Return the Charitable_User object for this donor.
     *
     * @return  Charitable_User
     * @access  public
     * @since   1.0.0
     */
    public function get_user() {
        if ( ! isset( $this->user ) ) {
            $this->user = $this->user = Charitable_User::init_with_donor( $this->donor_id );
        }

        return $this->user;
    }

    /**
     * Return the Charitable_Donation object associated with this object.  
     *
     * @return  Charitable_Donation|false
     * @access  public
     * @since   1.0.0
     */
    public function get_donation() {
        if ( ! isset( $this->donation ) ) {            
            $this->donation = $this->donation_id ? new Charitable_Donation( $this->donation_id ) : false;
        }

        return $this->donation;
    }

    /**
     * Return the Charitable_Donation object associated with this object.  
     *
     * @return  object[]
     * @access  public
     * @since   1.3.5
     */
    public function get_donations() {
        return $this->get_user()->get_donations();
    }

    /**
     * Return the donor meta stored for the particular donation. 
     *
     * @param   string $key Optional key passed to return a particular meta field.
     * @return  array|false
     * @access  public
     * @since   1.0.0
     */
    public function get_donor_meta( $key = '' ) {
        if ( ! $this->get_donation() ) {
            return false;
        }

        if ( ! isset( $this->donor_meta ) ) {
            $this->donor_meta = get_post_meta( $this->donation_id, 'donor', true );
        }

        if ( empty( $key ) ) {
            return $this->donor_meta;
        }

        return isset( $this->donor_meta[ $key ] ) ? $this->donor_meta[ $key ] : '';
    }

    /**
     * Return a value from the donor meta.
     *
     * @param   string $key
     * @return  mixed
     * @access  public
     * @since   1.2.4
     */
    public function get_value( $key ) {
        $meta = $this->get_donor_meta();

        if ( ! $meta || ! isset( $meta[ $key ] ) ) {
            return '';
        }

        return $meta[ $key ];
    }

    /**
     * Return the donor's name stored for the particular donation. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_name() {
        if ( ! $this->get_donor_meta() ) {
            return $this->get_user()->get_name();
        }

        $meta = $this->get_donor_meta();
        $first_name = isset( $meta[ 'first_name' ] ) ? $meta[ 'first_name' ] : '';
        $last_name = isset( $meta[ 'last_name' ] ) ? $meta[ 'last_name' ] : '';
        $name = trim( sprintf( '%s %s', $first_name, $last_name ) );

        return apply_filters( 'charitable_donor_name', $name, $this );
    }

    /**
     * Return the donor's email address. 
     *
     * @return  string
     * @access  public
     * @since   1.2.4
     */
    public function get_email() {
        return $this->get_value( 'email' );
    }

    /**
     * Return the donor's address. 
     *
     * @return  string
     * @access  public
     * @since   1.2.4
     */
    public function get_address() {
        return $this->get_user()->get_address( $this->donation_id );
    }

    /**
     * Return the donor avatar. 
     *
     * @param   int $size
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_avatar( $size = 100 ) {
        return apply_filters( 'charitable_donor_avatar', $this->get_user()->get_avatar(), $this );
    }

    /**
     * Return the donor location. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_location() {
        if ( ! $this->get_donor_meta() ) {
            return $this->get_user()->get_location();
        }

        $meta = $this->get_donor_meta();
        $city = isset( $meta[ 'city' ] ) ? $meta[ 'city' ] : '';
        $state = isset( $meta[ 'state' ] ) ? $meta[ 'state' ] : '';
        $country = isset( $meta[ 'country' ] ) ? $meta[ 'country' ] : '';
        
        $region = strlen( $city ) ? $city : $state;

        if ( strlen( $country ) ) {

            if ( strlen( $region ) ) {
                $location = sprintf( '%s, %s', $region, $country ); 
            }
            else {
                $location = $country;
            }
        }
        else {
            $location = $region;
        }

        return apply_filters( 'charitable_donor_location', $location, $this );
    }

    /**
     * Return the donation amount. 
     * 
     * If a donation ID was passed to the object constructor, this will return
     * the total donated with this particular donation. Otherwise, this will
     * return the total amount ever donated by the donor.
     *
     * @param   int $campaign_id Optional. If set, returns total donated to this particular campaign.
     * @return  decimal
     * @access  public
     * @since   1.0.0
     */
    public function get_amount( $campaign_id = false ) {
        if ( $this->get_donation() ) {
            return $this->get_donation_amount( $campaign_id );            
        }

        return $this->get_user()->get_total_donated( $campaign_id );
    }

    /**
     * Return the amount of the donation. 
     *
     * @param   int $campaign_id Optional. If set, returns the amount donated to the campaign.
     * @return  decimal
     * @access  public
     * @since   1.2.0
     */
    public function get_donation_amount( $campaign_id = "" ) {
        return charitable_get_table( 'campaign_donations' )->get_donation_amount( $this->donation_id, $campaign_id );
    }
}

endif; // End class_exists check