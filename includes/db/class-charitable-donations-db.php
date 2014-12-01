<?php 
/**
 * Charitable Donations DB class. 
 *
 * @package     Charitable
 * @subpackage  Classes/Charitable Donations DB
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donations_DB' ) ) : 

/**
 * Charitable_Donations_DB
 *
 * @since 		1.0.0 
 */
class Charitable_Donations_DB extends Charitable_DB {	

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
	public $primary_key = 'id';

	/**
	 * Set up the database table name. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'charitable_donations';
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
			'id'				=> '%d', 
			'campaign_id'		=> '%d',
			'user_id'			=> '%d',
			'date_created'		=> '%s',
			'amount'			=> '%f',
			'gateway'			=> '%s', 
			'is_preset_amount'	=> '%d', 
			'notes'				=> '%s', 
			'status'			=> '%s'
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
			'id'				=> '', 
			'campaign_id'		=> '',
			'user_id'			=> 0,
			'date_created'		=> date('Y-m-d h:i:s'),
			'amount'			=> '',
			'gateway'			=> '', 
			'is_preset_amount'	=> 0, 
			'notes'				=> '', 
			'status'			=> 'Pending'
		);
	}

	/**
	 * Valid donation statuses.
	 *
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_statuses() {
		return array(
			'Completed', 
			'Pending', 
			'Refunded', 
			'Revoked', 
			'Failed', 
			'Abandoned', 
			'Preapproval', 
			'Cancelled'
		);
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

		$sql = "CREATE TABLE " . $this->table_name . " (
		`id` bigint(20) NOT NULL AUTO_INCREMENT,
		`campaign_id` bigint(20) NOT NULL,
		`user_id` bigint(20) NOT NULL,
		`date_created` datetime NOT NULL,
		`amount` float NOT NULL,
		`gateway` varchar(50) NOT NULL,
		`is_preset_amount` tinyint NOT NULL,
		`notes` longtext NOT NULL,
		`status` varchar(20) NOT NULL,
		KEY (id),
		KEY user (user_id),
		KEY campaign (campaign_id)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

	/** 
	 * Add a new donation.
	 * 
	 * @param 	array 	$data
	 * @return 	int 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add( array $data ) {
		// Validate donation status
		if ( isset( $data['status'] ) && ! in_array( $data['status'], $this->get_statuses() ) ) {
			wp_die( __( sprintf( 'Invalid donation status "%s" supplied', $data['status'] ), 'charitable' ) );
		}

		return parent::insert( $data, 'donation' );
	}

	/**
	 * Get an object of all donations on a campaign
	 *
	 * @global 	wpdb 	$wpdb
	 * @param 	int 	$campaign_id
	 * @return 	object
	 * @since 	1.0.0
	 */
	public function get_donations_on_campaign( $campaign_id ){
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE campaign_id = %d;", $campaign_id ), OBJECT_K);
	}

	/**
	 * Get total amount donated to a campaign
	 *
	 * @global 	wpdb 	$wpdb
	 * @param 	int 	$campaign_id
	 * @return 	int 					
	 * @since 	1.0.0
	 */
	public function get_campaign_donated_amount( $campaign_id ){
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT SUM(amount) FROM $this->table_name WHERE campaign_id = %d;", $campaign_id ) );
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
	 	return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT user_id FROM $this->table_name WHERE campaign_id = %d;", $campaign_id ), OBJECT_K );
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
	 	return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(user_id) FROM $this->table_name WHERE campaign_id = %D;", $campaign_id ) );
	 }
}	

endif;