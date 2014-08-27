<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Campaign' ) ) : 

/**
 * Campaign model
 *
 * @class 		Charitable_Campaign
 * @version		0.1
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
	 * @param mixed $post The post ID or WP_Post object for this this campaign.
	 * @return void
	 * @access public
	 * @since 0.1
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
	 * @return int
	 * @access public
	 * @since 0.1
	 */
	public function get_campaign_id() {
		if ( ! isset( $this->ID ) ) {
			$this->ID = $this->post->ID;
		}

		return $this->ID;
	}

	/**
	 * Returns the campaign's post_meta values. 
	 *
	 * @see get_post_meta
	 * 
	 * @param string $meta_name The meta name to search for.
	 * @param bool $single Whether to return a single value or an array. 
	 * @return mixed This will return an array if single is false. If it's true, 
	 *  	the value of the meta_value field will be returned.
	 * @access public
	 * @since 0.1
	 */
	public function get( $meta_name, $single = true ) {
		return get_post_meta( $this->post->ID, $meta_name, $single );
	}

	/**
	 * Returns the timetamp of the end date.
	 *
	 * @return int 
	 * @access public
	 * @since 0.1
	 */
	public function get_end_time() {
		if ( ! isset( $this->end_time ) ) {
			$this->end_time = $this->get('campaign_end_time');
		}
		return $this->end_time;
	}

	/**
	 * Returns the end date in your preferred format.
	 *
	 * If a format is not provided, the user-defined date_format in Wordpress settings is used.
	 * 
	 * @param string $date_format A date format accepted by PHP's date() function.
	 * @return string 
	 * @access public
	 * @since 0.1
	 */
	public function get_end_date($date_format = '') {
		if ( ! strlen( $date_format ) ) {
			$date_format = get_option('date_format', 'd\/m\/Y');
		}

		/**
		 * Filter the end date format using the charitable_campaign_end_date_format hook.
		 */
		$date_format = apply_filters( 'charitable_campaign_end_date_format', $date_format, $this );

		$date = explode( "/",  $this->get_end_time() );
		// return $this->get_end_time();
		return date( $date_format, mktime( $date[1], $date[0], $date[2] ) );
	}

	/**
	 * Returns the amount of time left in the campaign in seconds.
	 *
	 * @return int $time_left
	 * @access public
	 * @since 0.1
	 */
	public function get_time_left() {
		return $this->get_end_time() - time();
	}

	/**
	 * Returns the fundraising goal of the campaign.
	 * 
	 * @return decimal
	 * @access public
	 * @since 0.1
	 */
	public function get_goal() {
		if ( ! isset( $this->goal ) ) {
			$this->goal = $this->get('campaign_goal');
		}

		return $this->goal;
	}

	/**
	 * Returns the fundraising goal formatted as a monetary amount. 
	 *
	 * @return string
	 * @access public
	 * @since 0.1
	 */
	public function get_monetary_goal() {
		return get_charitable()->get_currency_helper()->get_monetary_amount( $this->get_goal() );
	}

	/**
	 * Returns the key used for caching all donations made to this campaign.
	 * 
	 * @return string
	 * @access public
	 * @since 0.1
	 */
	private function get_donations_cache_key() {
		return 'charitable_campaign_' . $this->get_campaign_id() . '_donations';
	}

	/**
	 * Returns the donations made to this campaign. 
	 *
	 * @return WP_Query
	 * @access public
	 * @since 0.1
	 */
	public function get_donations() {
		if ( ! isset( $this->donations ) || is_null( $this->donations ) ) {

			/**
			 * Try to fetch from cache first.
			 */
			$cache_key = $this->get_donations_cache_key();
			$this->donations = get_transient( $cache_key );

			if ( $this->donations === false ) {

				/**
				 * Retrieves the donations using a WP_Query object. 
				 * 
				 * @uses charitable_campaign_donations_query_args 
				 */
				$this->donations = new WP_Query( 
					apply_filters( 'charitable_campaign_donations_query_args', 
						array(
							'post_type' => 'donation', 
							'post_status' => 'publish', 
							'posts_per_page' => -1,
							'meta_query' => array(
								array(
									'key' => 'campaign_id',
									'value' => $this->get_campaign_id() 
								)
							)
						)	
					) 
				);

				/**
				 * Cache the results.
				 */
				set_transient( $cache_key, $this->donations, 0 ); 
			}
		}

		return $this->donations;
	}

	/**
	 * Return the current amount of donations.
	 *
	 * @return int
	 * @access public
	 * @since 0.1
	 */
	public function get_donated_amount() {
		if ( ! isset( $this->donated_amount ) || is_null( $this->donated_amount ) ) {

			/**
			 * Try to fetch from cache first. 
			 */
			$cache_key = $this->get_donations_cache_key() . '_amount';
			$this->donated_amount = get_transient( $cache_key );

			if ( $this->donated_amount === false ) {

				global $wpdb;

				$this->donated_amount = $wpdb->get_var( $wpdb->prepare(
					"SELECT sum(m2.meta_value) 
					FROM $wpdb->postmeta m1
					INNER JOIN $wpdb->postmeta m2 
					ON m1.post_id = m2.post_id
					WHERE m1.meta_key = 'campaign_id'
					AND m1.meta_value = %d
					AND m2.meta_key = 'donation_amount'"
					, $this->get_campaign_id()
				) );
			}

			/**
			 * Cache the results. 
			 */
			set_transient( $cache_key, $this->donated_amount, 0 );
		}

		return $this->donated_amount;
	}

	/**
	 * Flush donations cache.
	 *
	 * @return void
	 * @access public
	 * @since 0.1
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
	 * @return Charitable_Donation_Form_Interface
	 * @access public
	 * @since 0.1
	 */
	public function get_donation_form() {
		if ( ! isset( $this->donation_form ) ) {

			$form_class = apply_filters( 'charitable_donation_form_class', 'Charitable_Donation_Form', $this );
			
			$this->donation_form = new $form_class( $this );
		}

		return $this->donation_form;
	}

	/**
	 * Renders the button to donate. 
	 * 
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function donate_button() {
		new Charitable_Template('campaign/donate-button');
	}
}

endif; // End class_exists check