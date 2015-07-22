<?php
/**
 * Donation model
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Donation
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donation' ) ) : 

/**
 * Donation Model
 *
 * @since		1.0.0
 */

class Charitable_Donation {
	
	/**
	 * The donation ID. 
	 *
	 * @var 	int 
	 * @access 	private
	 */
	private $donation_id;

	/**
	 * The database record for this donation from the Posts table.
	 * 
	 * @var 	Object 
	 * @access  private 
	 */
	private $donation_data;

	/**
	 * The Campaign Donations table.
	 *
	 * @var 	Charitable_Campaign_Donations_DB
	 * @access  private
	 */
	private $campaign_donations_db;	

	/**
	 * The payment gateway used to process the donation.
	 *
	 * @var 	Charitable_Gateway_Interface
	 * @access 	private
	 */
	private $gateway;

	/**
	 * The campaign donations made as part of this donation. 
	 *
	 * @var 	Object
	 * @access 	private
	 */
	private $campaign_donations;

	/**
	 * The WP_User object of the person who donated. 
	 * 
	 * @var 	WP_User 
	 * @access 	private
	 */
	private $donor;

	/**
	 * Instantiate a new donation object based off the ID.
	 * 
	 * @param 	mixed 		$donation 		The donation ID or WP_Post object.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct( $donation ) {
		if ( is_a( $donation, 'WP_Post' ) ) {
			$this->donation_id 			= $donation->ID;
			$this->donation_data 		= $donation;	
		}
		else {
			$this->donation_id 			= $donation;
			$this->donation_data 		= get_post( $donation );		
		}		
	}

	/**
	 * Magic getter.
	 *
	 * @param 	string 		$key
	 * @return 	mixed
	 * @access  public
	 * @since 	1.0.0
	 */
	public function __get( $key ) {
		if ( method_exists( $this, 'get_' . $key ) ) {
			$method = 'get_' . $key;
			return $this->$method;
		}

		return $this->donation_data->$key;
	}

	/**
	 * Return the donation number. By default, this is the ID, but it can be filtered. 
	 *
	 * @return  string
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_number() {
		return apply_filters( 'charitable_donation_number', $this->donation_id );
	}

	/**
	 * Get the donation data.
	 *
	 * @return 	Charitable_Campaign_Donations_DB
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaign_donations_db() {
		if ( ! isset( $this->campaign_donations_db ) ) {
			$this->campaign_donations_db = new Charitable_Campaign_Donations_DB();
		}

		return $this->campaign_donations_db;
	}

	/**
	 * The amount donated on this donation.
	 *
	 * @return 	float
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_total_donation_amount() {
		return $this->get_campaign_donations_db()->get_donation_total_amount( $this->donation_id );
	}

	/**
	 * Return the campaigns donated to in this donation. 
	 *
	 * @return 	object[]
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaign_donations() {
		if ( ! isset( $this->campaign_donations ) ) {
			$this->campaign_donations = $this->get_campaign_donations_db()->get_donation_records( $this->donation_id );
		}

		return $this->campaign_donations;
	}

	/**
	 * Returns an array of the campaigns that were donated to.
	 *
	 * @return 	string[]
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_campaigns() {
		return array_map( array( $this, 'get_campaign_name' ), $this->get_campaign_donations() );
	}

	/**
	 * Returns the campaign name from a campaign donation record.
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_campaign_name( $campaign_donation ) {
		return $campaign_donation->campaign_name;
	}

	/**
	 * Return a comma separated list of the campaigns that were donated to.	
	 *
	 * @param 	boolean 	$linked 		Whether to return the campaigns with links to the campaign pages.
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaigns_donated_to( $linked = false ) {
		$campaigns = $linked ? $this->get_campaigns_links() : $this->get_campaigns();

		return implode( ', ', $campaigns );
	}

	/**
	 * Return a comma separated list of the campaigns that were donated to, with links to the campaigns. 
	 *
	 * @return 	string[]
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaigns_links() {
		$links = array();

		foreach ( $this->get_campaign_donations() as $campaign ) {

			if ( ! isset( $links[ $campaign->campaign_id ] ) ) {

				$links[ $campaign->campaign_id ] = sprintf( '<a href="%s" title="%s">%s</a>', 
					get_permalink( $campaign->campaign_id ), 
					sprintf( '%s %s', _x( 'Go to', 'go to campaign', 'charitable' ), get_the_title( $campaign->campaign_id ) ), 
					get_the_title( $campaign->campaign_id )
				);

			}
		}

		return $links;
	}

	/**
	 * Return the date of the donation.
	 *
	 * @param 	string 		$format
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_date( $format = '' ) {
		if ( empty( $format ) ) {
			$format = get_option( 'date_format' );
		}

		return date_i18n( $format, strtotime( $this->donation_data->post_date ) );
	}

	/**
	 * The name of the gateway used to process the donation.
	 *
	 * @return 	string 	The key identifier of the donation.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_gateway() {
		return get_post_meta( $this->donation_id, 'donation_gateway', true );
	}

	/**
	 * The public label of the gateway used to process the donation. 
	 *
	 * @return  string
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_gateway_label() {
		$gateway = $this->get_gateway_object();

		if ( ! $gateway ) {
			return '';
		} 

		return $gateway->get_label();
	}

	/**
	 * Returns the gateway's object helper.	
	 *
	 * @return  Charitable_Gateway
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_gateway_object() {
		$class = charitable_get_helper( 'gateways' )->get_gateway( $this->get_gateway() );

		if ( ! $class ) {
			return false;
		} 

		return new $class;
	}

	/**
	 * The status of this donation.
	 *
	 * @param 	boolean 	$label 	Whether to return the label. If not, returns the key.
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_status( $label = false ) {
		$status = $this->donation_data->post_status;

		if ( ! $label ) {
			return $status;
		}

		$statuses = self::get_valid_donation_statuses();
		return $statuses[ $status ];
	} 

	/**
	 * Returns the donation ID. 
	 * 
	 * @return 	int
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_donation_id() {
		return $this->donation_id;
	}

	/**
	 * Returns the customer note attached to the donation.
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_notes() {
		return $this->donation_data->post_content;
	}

	/**
	 * Returns the donor ID of the donor. 
	 *
	 * @return  int
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_donor_id() {
		return current( $this->get_campaign_donations() )->donor_id;
	}

	/**
	 * Returns the donor who made this donation.
	 *
	 * @return 	Charitable_User
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_donor() {
		if ( ! isset( $this->donor ) ) {
			$this->donor = Charitable_User::init_with_donor( $this->get_donor_id() );
		}

		return $this->donor;
	}
	
	/**
	 * Return array of valid donations statuses. 
	 *
	 * @return 	array
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_valid_donation_statuses() {
		return apply_filters( 'charitable_donation_statuses', array( 
			'charitable-pending' 	=> __( 'Pending', 'charitable' ),
			'charitable-completed' 	=> __( 'Paid', 'charitable' ),
			'charitable-failed' 	=> __( 'Failed', 'charitable' ),
			'charitable-cancelled' 	=> __( 'Cancelled', 'charitable' ),
			'charitable-refunded' 	=> __( 'Refunded', 'charitable' ),
		) );
	}	

	/**
	 * Returns whether the donation status is valid. 
	 *
	 * @return  boolean
	 * @access  public
	 * @static
	 * @since   1.0.0
	 */
	public static function is_valid_donation_status( $status ) {
		return array_key_exists( $status, self::get_valid_donation_statuses() );
	}

	/**
	 * Returns the donation statuses that signify a donation was complete. 
	 *
	 * By default, this is just 'charitable-completed'. However, 'charitable-preapproval' 
	 * is also counted. 
	 *
	 * @return  string[]
	 * @access  public
	 * @static
	 * @since   1.0.0
	 */
	public static function get_approval_statuses() {
		return apply_filters( 'charitable_approval_donation_statuses', array( 'charitable-completed' ) );
	}

	/**
	 * Returns whether the passed status is an confirmed status. 
	 *
	 * @return 	boolean
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function is_approved_status( $status ) {
		return in_array( $status, self::get_approval_statuses() );
	}

	/**
	 * Inserts a new donation. 
	 *
	 * @param 	array 	$args
	 * @return 	int 	$donation_id 	Returns 0 in case of failure. Positive donation ID otherwise.
	 * @access 	public
	 * @static
	 * @since 	1.0.0
	 */
	public static function add_donation( array $args ) {
		$args = apply_filters( 'charitable_donation_args', $args );		

		if ( ! self::is_valid_campaign_data( $args ) ) {
			_doing_it_wrong( __METHOD__, 'A donation cannot be inserted without an array of campaigns being donated to.', '1.0.0' );
			return 0;
		}

		$donor_id = self::get_donor_id_for_donation( $args );

		if ( 0 == $donor_id ) {
			_doing_it_wrong( __METHOD__, 'A donation cannot be inserted without a valid donor id.', '1.0.0' );
			return 0;
		}		
		
		do_action( 'charitable_before_add_donation', $args );

		$donation_args = self::parse_donation_data( $args, $donor_id );		

		$donation_id = wp_insert_post( $donation_args );

		if ( is_wp_error( $donation_id ) ) {
			charitable_get_notices()->add_errors_from_wp_error( $donation_id );
			return 0;
		}

		if ( $donation_id > 0 ) {
			self::add_campaign_donations( $args[ 'campaigns' ], $donation_id, $donor_id );

			self::add_donation_meta( $donation_id, $args );				

			self::update_donation_log( $donation_id, __( 'Donation created.', 'charitable' ) );	

			do_action( 'charitable_after_add_donation', $donation_id, $donor_id, $args );
		}

		return $donation_id;
	}

	/**
	 * Inserts the campaign donations into the campaign_donations table. 
	 *
	 * @param 	array 	$campaigns
	 * @param 	int 	$donation_id
	 * @param 	int 	$donor_id
	 * @return 	int 	The number of donations inserted. 
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function add_campaign_donations( $campaigns, $donation_id, $donor_id ) {
		
		foreach ( $campaigns as $campaign ) {
			$campaign[ 'donor_id' ] = $donor_id;
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
	 * @param 	int 	$donation_id
	 * @param 	array 	$args
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function add_donation_meta( $donation_id, $args ) {		
		$meta_keys = array_intersect_key( self::get_mapped_meta_keys(), $args );

		foreach ( $meta_keys as $key => $meta_key ) {
			$value = apply_filters( 'charitable_sanitize_donation_meta', $args[ $key ], $meta_key );
			update_post_meta( $donation_id, $meta_key, $value );
		}
	}

	/**
	 * Add a message to the donation log. 
	 *
	 * @param 	string 		$message
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function update_donation_log( $donation_id, $message ) {
		$log = self::get_donation_log( $donation_id );

		$log[] = array( 
			'time'		=> time(), 
			'message'	=> $message
		);

		update_post_meta( $donation_id, '_donation_log', $log );
	}

	/**
	 * Get a donation's log.  
	 *
	 * @return 	array
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_donation_log( $donation_id ) {
		$log = get_post_meta( $donation_id, '_donation_log', true );;

		return is_array( $log ) ? $log : array();
	}

	/**
	 * Return an array of meta keys with alternative keys. 
	 *
	 * In frontend forms, the preference is for using the mapped meta keys, which 
	 * are the keys of the array this method returns. In the backend, the actual
	 * meta keys are used instead.
	 *
	 * @return 	string[]
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_mapped_meta_keys() {
		return apply_filters( 'charitable_donation_mapped_meta_keys', array(
			'gateway' => 'donation_gateway'
		) );
	}

	/**
	 * Sanitize meta values before they are persisted to the database. 
	 *
	 * @param  	mixed 	$value
	 * @param 	string 	$key
	 * @return 	mixed
	 * @access 	public
	 * @static
	 * @since 	1.0.0
	 */
	public static function sanitize_meta( $value, $key ) {
		if ( 'donation_gateway' == $key ) {			
			if ( empty( $value ) || ! $value ) {
				$value = 'manual';
			}			
		}

		return apply_filters( 'charitable_sanitize_donation_meta-' . $key, $value );
	}

	/**
	 * Update the status of the donation. 
	 *	
	 * @uses 	wp_update_post()
	 * @param 	string 		$new_status
	 * @return 	int|WP_Error 					The value 0 or WP_Error on failure. The donation ID on success.
	 * @access  public
	 * @since 	1.0.0
	 */
	public function update_status( $new_status ) {		
		if ( false === self::is_valid_donation_status( $new_status ) ) {
			$new_status = array_search( $new_status, self::get_valid_donation_statuses() );

			if ( false === $new_status ) {
				_doing_it_wrong( __METHOD__, sprintf( '%s is not a valid donation status.', $new_status ), '1.0.0' );
				return 0;
			}
		}

		$old_status = $this->get_status();		

		if ( $old_status == $new_status ) {
			return 0;
		}		

		/* This actually updates the post status */
		$this->donation_data->post_status = $new_status;
		$donation_id = wp_update_post( $this->donation_data );

		self::update_donation_log( $donation_id, sprintf( __( 'Donation status updated from %s to %s', 'charitable' ), $valid_statuses[$old_status], $valid_statuses[$new_status] ) );

		do_action( 'charitable_after_update_donation', $donation_id, $new_status );

		return $donation_id;
	}

	/**
	 * Returns the donor ID for a new donation. 
	 *
	 * @param 	array 	$args
	 * @return 	int
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_donor_id_for_donation( $args ) {		
		if ( isset( $args[ 'donor_id' ] ) ) {			
			return $args[ 'donor_id' ];
		}

		$user_id = isset( $args[ 'user_id' ] ) ? $args[ 'user_id' ] : get_current_user_id();

		$donor_id = 0;

		if ( $user_id ) {
			$donor_id = charitable_get_table( 'donors' )->get_donor_id( $user_id );
		}
		elseif ( isset( $args[ 'email' ] ) ) {
			$donor_id = charitable_get_table( 'donors' )->get_donor_id_by_email( $args[ 'email' ] );
		}

		if ( 0 == $donor_id ) {
			$user = new Charitable_User( $user_id );
			$donor_id = $user->add_donor( $args );
		}
		
		return $donor_id;
	}

	/**
	 * Validate campaign data passed to insert. 
	 *
	 * @param 	array 	$args
	 * @return  boolean
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function is_valid_campaign_data( $args ) {
		return isset( $args['campaigns'] ) && is_array( $args['campaigns'] );
	}

	/**
	 * Receives the passed arguments and returns the donation content. 
	 *
	 * @param 	array 	$args
	 * @return  string
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_content( $args ) {
		$ret = isset( $args[ 'note' ] ) ? $args[ 'note' ] : '';
		return apply_filters( 'charitable_donation_data_content', $ret, $args );
	}

	/**
	 * Receives the passed arguments and returns the donation parent. 
	 *
	 * @param 	array 	$args
	 * @return  string
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_parent( $args ) {
		$ret = isset( $args[ 'donation_plan' ] ) ? $args[ 'donation_plan' ] : 0;
		return apply_filters( 'charitable_donation_data_post_parent', $ret, $args );
	}

	/**
	 * Receives the passed arguments and returns the donation date. 
	 *
	 * @param 	array 	$args
	 * @return  string
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_date( $args ) {
		$ret = isset( $args[ 'date' ] ) ? $args[ 'date' ] : date('Y-m-d h:i:s');
		return apply_filters( 'charitable_donation_data_post_date', $ret, $args );
	}

	/**
	 * Receives the passed arguments and returns the donation title. 
	 *
	 * @param 	array 	$args
	 * @return  string
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_title( $args ) {
		$ret = sprintf( '%s &ndash; %s', __( 'Donation', 'charitable' ), date( 'j F Y H:i a', strtotime( self::parse_donation_date( $args ) ) ) );
		return apply_filters( 'charitable_donation_data_post_title', $ret, $args );
	}

	/**
	 * Receives the passed arguments and returns the donation status. 
	 *
	 * @param 	array 	$args
	 * @return  string
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_status( $args ) {
		$ret = 'charitable-pending';

		/* Override if a valid status was set */
		if ( isset( $args[ 'status' ] ) && self::is_valid_donation_status( $args[ 'status' ] ) ) {
			$ret = $args[ 'status' ];
		}

		return apply_filters( 'charitable_donation_data_post_status', $ret, $args );
	}

	/**
	 * Parse the donation data, based on the passed $args array. 
	 *
	 * @param 	array 	$args
	 * @param 	int 	$donor_id
	 * @return  array
	 * @access  private
	 * @static
	 * @since   1.0.0
	 */
	private static function parse_donation_data( $args, $donor_id ) {
		if ( isset( $args[ 'user_id' ] ) ) {
			$user_id = $args[ 'user_id' ];
		}
		else {
			$user_id = charitable_get_table( 'donors' )->get_user_id( $donor_id );
		}

		$donation_args = array(
			'post_type'		=> 'donation', 
			'post_author'	=> $user_id, 
			'post_status'	=> self::parse_donation_status( $args ),
			'post_content'	=> self::parse_donation_content( $args ), 
			'post_parent'	=> self::parse_donation_parent( $args ),
			'post_date'		=> self::parse_donation_date( $args ),
			'post_title'	=> self::parse_donation_title( $args )
		);						

		$donation_args[ 'post_date_gmt' ] = get_gmt_from_date( $donation_args[ 'post_date' ] );

		return apply_filters( 'charitable_donation_data', $donation_args, $args );
	}

}

endif; // End class_exists check