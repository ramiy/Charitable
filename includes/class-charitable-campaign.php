<?php
/**
 * Campaign model
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Campaign
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Campaign' ) ) : 

/**
 * Campaign Model
 *
 * @since		1.0.0
 */
class Charitable_Campaign {

	/**
	 * @var WP_Post The WP_Post object associated with this campaign.
	 */
	private $post;

	/**
	 * @var int The ID of the campaign. 
	 */
	private $ID;

	/**
	 * @var int The timestamp for the expiry for this campaign.
	 */
	private $end_time;

	/**	 
	 * @var decimal The fundraising goal for the campaign.
	 */
	private $goal;

	/**
	 * @var WP_Query The donations made to this campaign. 
	 */
	private $donations;

	/**
	 * @var int The amount donated to the campaign. 
	 */
	private $donated_amount;

	/**
	 * @var Charitable_Donation_Form The form object for this campaign.
	 */
	private $donation_form;

	/**
	 * Class constructor. 
	 * 
	 * @param 	mixed 	$post 		The post ID or WP_Post object for this this campaign.
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct($post) {
		if ( ! is_a( $post, 'WP_Post' ) ) {
			$post = get_post( $post );
		}

		$this->post = $post;
	}

	/**
	 * Magic getter.  
	 *
	 * @return 	mixed
	 * @access  public
	 * @since 	1.0.0
	 */
	public function __get( $key ) {
		if ( property_exists( $this->post, $key ) ) {
			return $this->post->$key;
		}

		return $this->get( $key );
	}

	/**
	 * Returns the campaign's post_meta values. An underscore is automatically prepended to the meta key.
	 *
	 * @see 	get_post_meta
	 * 
	 * @param 	string 	$meta_name 		The meta name to search for.
	 * @param 	bool 	$single 		Whether to return a single value or an array. 
	 * @return 	mixed 					This will return an array if single is false. If it's true, 
	 *  								the value of the meta_value field will be returned.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get( $meta_name, $single = true ) {
		$meta_name = '_' . $meta_name;
		return get_post_meta( $this->post->ID, $meta_name, $single );
	}

	/**
	 * Returns the campaign's ID. 
	 * 
	 * @return 	int
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_campaign_id() {
		if ( ! isset( $this->ID ) ) {
			$this->ID = $this->post->ID;
		}

		return $this->ID;
	}
	

	/**
	 * Returns whether the campaign is endless (i.e. no end date has been set). 
	 *
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function is_endless() {
		return 0 == $this->get('campaign_end_date');
	}

	/**
	 * Returns the end date in your preferred format.
	 *
	 * If a format is not provided, the user-defined date_format in Wordpress settings is used.
	 * 
	 * @param 	string 	$date_format 	A date format accepted by PHP's date() function.
	 * @return 	string|false 		String if an end date is set. False if campaign has no end date. 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_end_date($date_format = '') {
		if ( $this->is_endless() ) {
			return false;
		}

		if ( ! strlen( $date_format ) ) {
			$date_format = get_option('date_format', 'd/m/Y');
		}

		/**
		 * Filter the end date format using the charitable_campaign_end_date_format hook.
		 */
		$date_format = apply_filters( 'charitable_campaign_end_date_format', $date_format, $this );

		/**
		 * This is how the end date is stored in the database, so just return that directly.
		 */
		if ( 'Y-m-d H:i:s' == $date_format ) {
			return $this->get('campaign_end_date');
		}
		
		return date( $date_format, $this->get_end_time() );
	}

	/**
	 * Returns the timetamp of the end date.
	 *
	 * @return 	int|false  			Int if campaign has an end date. False if campaign has no end date.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_end_time() {
		if ( ! isset( $this->end_time ) ) {

			if ( $this->is_endless() ) {
				return false;
			}

			/**
			 * The date is stored in the format of Y-m-d H:i:s.
			 */
			$date_time 	= explode( ' ', $this->get('campaign_end_date') );
			$date 		= explode( '-', $date_time[0] );
			$time 		= explode( ':', $date_time[1] );
			$this->end_time = mktime( $time[0], $time[1], $time[2], $date[1], $date[2], $date[0] );
		}
		return $this->end_time;
	}

	/**
	 * Returns the amount of time left in the campaign in seconds.
	 *
	 * @return 	int $time_left 		Int if campaign has an end date. False if campaign has no end date.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_seconds_left() {
		if ( $this->is_endless() ) {
			return false;
		}

		$time_left = $this->get_end_time() - current_time( 'timestamp' );
		return $time_left < 0 ? 0 : $time_left;	
	}

	/**
	 * Returns the amount of time left in the campaign as a descriptive string. 
	 *
	 * @uses charitable_campaign_ended 			Change the text displayed when there is no time left.
	 * @uses charitabile_campaign_minutes_left 	Change the text displayed when there is less than an hour left.
	 * @uses charitabile_campaign_hours_left 	Change the text displayed when there is less than a day left.
	 * @uses charitabile_campaign_days_left 	Change the text displayed when there is more than a day left.
	 * @uses charitable_campaign_time_left 		Change the text displayed when there is time left. This will 
	 *
	 * @return 	string 		
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_time_left() {
		if ( $this->is_endless() ) {
			return '';
		}

		$hour = 3600; 
		$day = 86400;

		$seconds_left = $this->get_seconds_left();		

		/**
		 * Condition 1: The campaign has finished.
		 */ 
		if ( $seconds_left === 0 ) {
			$time_left = apply_filters( 'charitable_campaign_ended', __( 'Campaign has ended', 'charitable' ), $this );
		}
		/**
		 * Condition 2: There is less than an hour left.
		 */
		elseif ( $seconds_left <= $hour ) {
			$minutes_remaining = ceil( $seconds_left / 60 );
			$time_left = apply_filters( 'charitabile_campaign_minutes_left', 
				sprintf( _n('%s Minute Left', '%s Minutes Left', $minutes_remaining, 'charitable'), '<span class="amount time-left minutes-left">' . $minutes_remaining . '</span>' ), 
				$this
			);
		}
		/**
		 * Condition 3: There is less than a day left. 
		 */
		elseif ( $seconds_left <= $day ) {
			$hours_remaining = floor( $seconds_left / 3600 );
			$time_left = apply_filters( 'charitabile_campaign_hours_left', 
				sprintf( _n('%s Hour Left', '%s Hours Left', $hours_remaining, 'charitable'), '<span class="amount time-left hours-left">' . $hours_remaining . '</span>' ), 
				$this
			);
		}
		/**
		 * Condition 4: There is more than a day left.
		 */
		else {
			$days_remaining = floor( $seconds_left / 86400 );
			$time_left = apply_filters( 'charitabile_campaign_days_left', 
				sprintf( _n('%s Day Left', '%s Days Left', $days_remaining, 'charitable'), '<span class="amount time-left days-left">' . $days_remaining . '</span>' ),
				$this
			);
		}

		return apply_filters( 'charitable_campaign_time_left', $time_left, $this );	
	}	

	/**
	 * Returns whether the campaign has ended. 
	 *
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function has_ended() {
		return 0 == $this->get_seconds_left();
	}

	/**
	 * Return the time since the campaign finished, or zero if it's still going. 
	 *
	 * @return 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_time_since_ended() {
		if ( 0 !== $this->get_seconds_left() ) {
			return 0;
		}

		return current_time( 'timestamp' ) - $this->get_end_time();
	}

	/**
	 * Returns the fundraising goal of the campaign.
	 * 
	 * @return 	decimal|false 		Decimal if goal is set. False if no goal has been set. 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_goal() {
		if ( ! isset( $this->goal ) ) {
			$this->goal = $this->has_goal() ? $this->get('campaign_goal') : false;
		}

		return $this->goal;
	}

	/**
	 * Returns whether a goal has been set (anything greater than $0 is a goal).
	 * 
	 * @return 	boolean
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function has_goal() {
		return 0 < $this->get('campaign_goal');
	}	

	/**
	 * Returns the fundraising goal formatted as a monetary amount. 
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_monetary_goal() {
		if ( ! $this->has_goal() ) {
			return '';
		}

		return charitable()->get_currency_helper()->get_monetary_amount( $this->get_goal() );
	}

	/**
	 * Returns whether the goal has been achieved. 
	 *
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function has_achieved_goal() {
		return $this->get_donated_amount() >= $this->get_goal();
	}

	/**
	 * Returns the key used for caching all donations made to this campaign.
	 * 
	 * @param 	int 		$campaign_id
	 * @return 	string
	 * @access 	public
	 * @static
	 * @since 	1.0.0
	 */
	public static function get_donations_cache_key( $campaign_id ) {
		return 'charitable_campaign_' . $campaign_id . '_donations';
	}

	/**
	 * Returns the donations made to this campaign. 
	 *
	 * @return 	WP_Query
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_donations() {
		if ( ! isset( $this->donations ) || is_null( $this->donations ) ) {

			/**
			 * Try to fetch from cache first.
			 */
			$this->donations = get_transient( $this->get_donations_cache_key( $this->get_campaign_id() ) );

			if ( false === $this->donations ) {

				$this->donations = charitable()->get_db_table('campaign_donations')->get_donations_on_campaign( $this->get_campaign_id() );
	
				set_transient( $this->get_donations_cache_key( $this->get_campaign_id() ), $this->donations, 0 ); 
			}
		}

		return $this->donations;
	}

	/**
	 * Return the current amount of donations.
	 *
	 * @return 	int
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_donated_amount() {
		if ( ! isset( $this->donated_amount ) || is_null( $this->donated_amount ) ) {

			/**
			 * Try to fetch from cache first. 
			 */
			$this->donated_amount = get_transient( $this->get_donations_cache_key( $this->get_campaign_id() ) . '_amount' );

			if ( $this->donated_amount === false ) {
				$this->donated_amount = charitable()->get_db_table('campaign_donations')->get_campaign_donated_amount( $this->get_campaign_id() );

				set_transient( $this->get_donations_cache_key( $this->get_campaign_id() ) . '_amount', $this->donated_amount, 0 );
			}			
		}

		return $this->donated_amount;
	}

	/**
	 * Return the percentage donated. Use this if you want a formatted string.
	 *
	 * @return 	string|false 		String if campaign has a goal. False if no goal is set.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_percent_donated() {
		$percent = $this->get_percent_donated_raw();

		if ( false === $percent ) {
			return $percent;
		}		

		return apply_filters( 'charitable_percent_donated', $percent.'%', $percent, $this );
	}

	/**
	 * Returns the percentage donated as a number.
	 *
	 * @return 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_percent_donated_raw() {
		if ( ! $this->has_goal() ) {
			return false;
		}

		return ( $this->get_donated_amount() / $this->get_goal() ) * 100;
	}

	/**
	 * Return the number of people who have donated to the campaign. 
	 *
	 * @return 	int
	 * @since 	1.0.0
	 */
	public function get_donor_count() {
		return charitable()->get_db_table('campaign_donations')->count_campaign_donors( $this->get_campaign_id() );
	}

	/**
	 * Flush donations cache.
	 *
	 * @param 	int 	$campaign_id
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function flush_donations_cache() {
		delete_transient( self::get_donations_cache_key( $this->get_campaign_id() ) );
		delete_transient( self::get_donations_cache_key( $this->get_campaign_id() ) . '_amount' );

		$this->donations = null;
		$this->donated_amount = null;
	}

	/**
	 * Returns the donation form object. 
	 * 
	 * @return 	Charitable_Donation_Form_Interface
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_donation_form() {
		if ( ! isset( $this->donation_form ) ) {

			$form_class = apply_filters( 'charitable_donation_form_class', 'Charitable_Donation_Form', $this );
			
			$this->donation_form = new $form_class( $this );
		}

		return $this->donation_form;
	}

	/**
	 * Returns any suggested amounts, or an empty array if none have been set. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_suggested_amounts() {
		$amounts = $this->get( 'campaign_suggested_donations' );

		if ( false === strpos( $amounts, '|' ) ) {
			return array();
		}

		return explode( '|', $amounts );
	}
}

endif; // End class_exists check