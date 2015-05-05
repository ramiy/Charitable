<?php 
/**
 * Charitable Campaign Donations DB class. 
 *
 * @package     Charitable/Classes/Charitable_Campaign_Donations_DB
 * @version    	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Campaign_Donations_DB' ) ) : 

/**
 * Charitable_Campaign_Donations_DB
 *
 * @since 		1.0.0 
 */
class Charitable_Campaign_Donations_DB extends Charitable_DB {	

	/**
	 * The version of our database table
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public $version = '1.0.0';

	/**
	 * The name of the primary column
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public $primary_key = 'campaign_donation_id';

	/**
	 * Set up the database table name. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'charitable_campaign_donations';
	}

	/**
	 * Whitelist of columns.
	 *
	 * @return  array 
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_columns() {
		return array(
			'campaign_donation_id'	=> '%d', 
			'donation_id'			=> '%d',
			'campaign_id'			=> '%d',
			'campaign_name'			=> '%s',
			'amount'				=> '%f'
		);
	}

	/**
	 * Default column values.
	 *
	 * @return 	array
	 * @access  public
	 * @since   1.0.0
	 */
	public function get_column_defaults() {
		return array(
			'campaign_donation_id'	=> '', 
			'donation_id'			=> '',
			'campaign_id'			=> '',
			'campaign_name'			=> '',
			'amount'				=> '',			
		);
	}

	/** 
	 * Add a new campaign donation.
	 * 
	 * @param 	array 		$data
	 * @return 	int 		The ID of the inserted campaign donation
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function insert( $data, $type = 'campaign_donation' ) {
		
		/* Ensure the campaign name is set */
		if ( ! isset( $data[ 'campaign_name' ] ) ) {

			$data[ 'campaign_name' ] = get_the_title( $data[ 'campaign_id' ] );

		}

		$this->flush_campaign_cache( $data['campaign_id'] );

		return parent::insert( $data, $type );
	}

	/**
	 * Update campaign donation record. 
	 *
	 * @param 	int 		$row_id
	 * @param 	array 		$data
	 * @param 	string 		$where 			Column used in where argument.
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function update( $row_id, $data = array(), $where = '' ) {
		if ( empty( $where ) ) {
			$this->flush_campaign_cache( $row_id );
		}

		return parent::update( $row_id, $data, $where );
	}

	/**
	 * Delete a row identified by the primary key.
	 *
	 * @param 	int 		$row_id
	 * @access  public
	 * @since   1.0.0
	 * @return  bool
	 */
	public function delete( $row_id = 0 ) {	
		$this->flush_campaign_cache( $row_id );

		return parent::delete( $row_id );
	}

	/**
	 * Flush a campaign's cache after insert/update/delete. 
	 *
	 * @param 	int 		$campaign_id
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function flush_campaign_cache( $campaign_id ) {
		delete_transient( Charitable_Campaign::get_donations_cache_key( $campaign_id ) );
		delete_transient( Charitable_Campaign::get_donations_cache_key( $campaign_id ) . '_amount' );
	}

	/**
	 * Get an object of all campaign donations associated with a single donation. 
	 *
	 * @global 	wpdb		$wpdb
	 * @return 	Object
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_donation_records( $donation_id ) {
		global $wpdb;

		$sql = "SELECT * 
				FROM $this->table_name 
				WHERE donation_id = %d;";

		return $wpdb->get_results( $wpdb->prepare( $sql, intval( $donation_id ) ), OBJECT_K );				
	}

	/**
	 * Get the total amount donated in a single donation. 
	 *
	 * @global 	$wpdb
	 * @param 	int 		$donation_id
	 * @return 	float
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_donation_total_amount( $donation_id ) {
		global $wpdb;

		$sql = "SELECT SUM(amount) 
				FROM $this->table_name 
				WHERE donation_id = %d;";

		return $wpdb->get_var( $wpdb->prepare( $sql, intval( $donation_id ) ) );
	}

	/**
	 * Get an object of all donations on a campaign.
	 *
	 * @global 	wpdb 	$wpdb
	 * @param 	int 	$campaign_id
	 * @return 	object
	 * @since 	1.0.0
	 */
	public function get_donations_on_campaign( $campaign_id ){
		global $wpdb;
		return $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT * 
				FROM $this->table_name 
				WHERE campaign_id = %d;", 
				$campaign_id 
			), OBJECT_K);
	}

	/**
	 * Get total amount donated to a campaign.
	 *
	 * @global 	wpdb 	$wpdb
	 * @param 	int 	$campaign_id
	 * @return 	int 					
	 * @since 	1.0.0
	 */
	public function get_campaign_donated_amount( $campaign_id ){
		global $wpdb;
		return $wpdb->get_var( 
			$wpdb->prepare( 
				"SELECT SUM(amount) 
				FROM $this->table_name 
				WHERE campaign_id = %d;", 
				$campaign_id 
			) );
	}

	/**
	 * The users who have donated to the given campaign.
	 *
	 * @global 	wpdb	$wpdb
	 * @param 	int 	$campaign_id
	 * @return 	object
	 * @since 	1.0.0
	 */
	public function get_campaign_donors( $campaign_id ) {
		global $wpdb;
		return $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT DISTINCT p.post_author as donor_id
				FROM $this->table_name c
				INNER JOIN {$wpdb->prefix}posts p
				ON c.donation_id = p.ID
				WHERE c.campaign_id = %d;", 
				$campaign_id
			), OBJECT_K );
	} 	 

	 /**
	  * Return the number of users who have donated to the given campaign. 
	  *
	  * @global wpdb	$wpdb
	  * @param 	int 	$campaign_id
	  * @return int
	  * @since 	1.0.0
	  */
	public function count_campaign_donors( $campaign_id ) {
		global $wpdb;
		return $wpdb->get_var( 
			$wpdb->prepare( 
				"SELECT COUNT(DISTINCT p.post_author) 
				FROM $this->table_name c
				INNER JOIN {$wpdb->prefix}posts p
				ON c.donation_id = p.ID
				WHERE c.campaign_id = %d;", 
				$campaign_id 
			) );
	}

	/**
	 * Return all donations made by a donor. 
	 *
	 * @global	wpdb	$wpdb
	 * @param 	int 	$donor_id
	 * @return 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_donations_by_donor( $donor_id ) {
		global $wpdb;

		$sql = "SELECT c.campaign_donation_id, c.donation_id, c.campaign_id, c.campaign_name, c.amount
				FROM $this->table_name c
				INNER JOIN {$wpdb->prefix}posts p
				ON c.donation_id = p.ID
				WHERE p.post_author = %d
				AND p.post_type = 'donation';";

		return $wpdb->get_results( $wpdb->prepare( $sql, $donor_id ), OBJECT_K );
	}

	/**
	 * Return total amount donated by a donor. 
	 *
	 * @global	wpdb	$wpdb
	 * @param 	int 	$donor_id
	 * @return 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_total_donated_by_donor( $donor_id ) {
		global $wpdb;

		$sql = "SELECT SUM(c.amount)
				FROM $this->table_name c
				INNER JOIN {$wpdb->prefix}posts p
				ON c.donation_id = p.ID
				WHERE p.post_author = %d
				AND p.post_type = 'donation';";

		return $wpdb->get_var( $wpdb->prepare( $sql, $donor_id ) );
	}

	/**
	 * Count the number of donations made by the donor.  
	 *
	 * @global	wpdb	$wpdb
	 * @param 	int 	$donor_id
	 * @param 	boolean $distinct_campaigns 	If true, will not count multiple donations to the same campaign.
	 * @return 	int
	 * @access  public
	 * @since 	1.0.0
	 */
	public function count_donations_by_donor( $donor_id, $distinct_campaigns = false ) {
		global $wpdb;
		
		$sql = "SELECT COUNT(*)
				FROM $this->table_name c
				INNER JOIN {$wpdb->prefix}posts p
				ON c.donation_id = p.ID
				AND p.post_author = %d
				AND p.post_type = 'donation';";

		return $wpdb->get_var( $wpdb->prepare( $sql, $donor_id ) );
	}

	/**
	 * Create the table.
	 *
	 * @global 	$wpdb
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function create_table() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = <<<EOD
CREATE TABLE IF NOT EXISTS {$this->table_name} (
`campaign_donation_id` bigint(20) NOT NULL AUTO_INCREMENT,
`donation_id` bigint(20) NOT NULL,
`campaign_id` bigint(20) NOT NULL,
`campaign_name` text NOT NULL,
`amount` float NOT NULL,
PRIMARY KEY (campaign_donation_id),
KEY donation (donation_id),
KEY campaign (campaign_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
EOD;

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}
}	

endif;