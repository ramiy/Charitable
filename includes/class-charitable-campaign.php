<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Campaign' ) ) : 

/**
 * Campaign model
 *
 * @class 		Charitable_Campaign
 * @version		1.0.0
 * @package		Charitable/Classes/Campaign
 * @category	Class
 * @author 		Studio164a
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
	 * Returns the end date in your preferred format.
	 *
	 * If a format is not provided, the user-defined date_format in Wordpress settings is used.
	 * 
	 * @param 	string 	$date_format 	A date format accepted by PHP's date() function.
	 * @return 	string 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_end_date($date_format = '') {
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
	 * @return 	int 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_end_time() {
		if ( ! isset( $this->end_time ) ) {
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
	 * @return 	int $time_left
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_seconds_left() {
		$time_left = $this->get_end_time() - time();
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
	 *											override any of the above filters.
	 *
	 * @return 	string 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_time_left() {
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
	 * Returns the fundraising goal of the campaign.
	 * 
	 * @return 	decimal
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_goal() {
		if ( ! isset( $this->goal ) ) {
			$this->goal = $this->get('campaign_goal');
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
		return ( 0 < $this->get_goal() );
	}	

	/**
	 * Returns the fundraising goal formatted as a monetary amount. 
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_monetary_goal() {
		return get_charitable()->get_currency_helper()->get_monetary_amount( $this->get_goal() );
	}

	/**
	 * Returns the key used for caching all donations made to this campaign.
	 * 
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	private function get_donations_cache_key() {
		return 'charitable_campaign_' . $this->get_campaign_id() . '_donations';
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
			$this->donations = get_transient( $this->get_donations_cache_key() );

			if ( false === $this->donations ) {

				$this->donations = get_charitable()->get_db_table('campaign_donations')->get_donations_on_campaign( $this->get_campaign_id() );
	
				set_transient( $this->get_donations_cache_key(), $this->donations, 0 ); 
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
			$this->donated_amount = get_transient( $this->get_donations_cache_key() . '_amount' );

			if ( $this->donated_amount === false ) {
				$this->donated_amount = get_charitable()->get_db_table('campaign_donations')->get_campaign_donated_amount( $this->get_campaign_id() );

				set_transient( $this->get_donations_cache_key() . '_amount', $this->donated_amount, 0 );
			}			
		}

		return $this->donated_amount;
	}

	/**
	 * Return the percentage donated. 
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_percent_donated() {
		if ( 0 == $this->get_goal() ) {
			return '';
		}

		$percent = ( $this->get_donated_amount() / $this->get_goal() ) * 100;

		return apply_filters( 'charitable_percent_donated', $percent.'%', $percent, $this );
	}

	/**
	 * Return the number of people who have donated to the campaign. 
	 *
	 * @return 	int
	 * @since 	1.0.0
	 */
	public function get_donor_count() {
		return get_charitable()->get_db_table('campaign_donations')->count_campaign_donors( $this->get_campaign_id() );
	}

	/**
	 * Flush donations cache.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function flush_donations_cache() {
		delete_transient( $this->get_donations_cache_key() );
		delete_transient( $this->get_donations_cache_key() . '_amount' );

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