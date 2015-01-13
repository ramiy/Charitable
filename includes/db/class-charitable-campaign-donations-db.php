<?php 
/**
 * Charitable Campaign Donations DB class. 
 *
 * @package     Charitable
 * @subpackage  Classes/Charitable Campaign Donations DB
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
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
	 * Add a new donation.
	 * 
	 * @param 	array 	$data
	 * @return 	int 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add( array $data ) {
		return parent::insert( $data, 'campaign_donation' );
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
		return $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT * 
				FROM $this->table_name 
				WHERE campaign_id = %d;", 
				$campaign_id 
			), OBJECT_K);
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
				"SELECT DISTINCT user_id 
				FROM $this->table_name 
				WHERE campaign_id = %d;", 
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
				"SELECT COUNT(user_id) 
				FROM $this->table_name 
				WHERE campaign_id = %d;", 
				$campaign_id 
			) );
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
CREATE TABLE {$this->table_name} (
`campaign_donation_id` bigint(20) NOT NULL AUTO_INCREMENT,
`donation_id` bigint(20) NOT NULL,
`campaign_id` bigint(20) NOT NULL,
`campaign_name` text NOT NULL,
`amount` float NOT NULL,
KEY (campaign_donation_id),
KEY donation (donation_id),
KEY campaign (campaign_id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
EOD;

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}	 
}	

endif;