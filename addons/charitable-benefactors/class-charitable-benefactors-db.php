<?php 
/**
 * Charitable Benefactors DB class. 
 *
 * @package     Charitable/Classes/Charitable_Benefactors_DB
 * @version  	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Benefactors_DB' ) ) : 

/**
 * Charitable_Benefactors_DB
 *
 * @since 		1.0.0 
 */
class Charitable_Benefactors_DB extends Charitable_DB {	

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
	public $primary_key = 'campaign_benefactor_id';

	/**
	 * Set up the database table name. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'charitable_benefactors';
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
			'campaign_benefactor_id'			=> '%d', 
			'campaign_id'						=> '%d',
			'contribution_amount'				=> '%f', 
			'contribution_amount_is_percentage'	=> '%d', 
			'date_created'						=> '%s', 
			'date_modified'						=> '%s', 
			'is_active'							=> '%d'
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
			'contribution_amount_is_percentage'	=> 1,
			'date_created'						=> '0000-00-00 00:00:00', 
			'date_modified'						=> '0000-00-00 00:00:00', 
			'is_active'							=> 1		
		);
	}

	/** 
	 * Add a new benefactor object.
	 * 
	 * @param 	array 	$data
	 * @return 	int|false
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function insert( $data, $type = 'campaign_benefactor' ) {
		/* Allow plugins to filter the data before inserting to database */
		$data = apply_filters( 'charitable_benefactor_data', $data );

		/* An array detailing the benefactor must be provided. */
		if ( ! isset( $data['benefactor'] ) || ! is_array( $data['benefactor'] ) || empty( $data['benefactor'] ) ) {

			_doing_it_wrong( __METHOD__, 'Campaign benefactors cannot be created without benefactor details.', '1.0.0' );		
			return false;

		}

		/* A contribution amount must be set */
		if ( empty( $data['contribution_amount'] ) || ! is_numeric ( $data['contribution_amount'] ) ) {

			_doing_it_wrong( __METHOD__, 'Campaign benefactors cannot be created without a contribution amount.', '1.0.0' );			
			return false;

		}		

		/* Pull out the benefactor details. These are passed to the 3rd party plugins */
		$benefactor_details = $data['benefactor'];

		unset( $data['benefactor'] );

		/* Create the record */
		$campaign_benefactor_id = parent::insert( $data, $type );

		/* Allow plugins to hook into this event */ 
		do_action( 'charitable_benefactor_added', $campaign_benefactor_id, $benefactor_details, $data );

		return $campaign_benefactor_id;
	}

	/**
	 * Get all active benefactors for a campaign. 
	 *
	 * @global 	WPDB 		$wpdb
	 * @param 	int 		$campaign_id
	 * @return 	Object
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_campaign_benefactors( $campaign_id ) {
		global $wpdb;

		return $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT * 
				FROM $this->table_name 
				WHERE campaign_id = %d
				AND is_active = 1;", 
				$campaign_id 
			), OBJECT_K);
	}

	/**
	 * Create the table.
	 *
	 * @global 	$wpdb
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function create_tables() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = <<<EOD
CREATE TABLE IF NOT EXISTS {$this->table_name} (
`campaign_benefactor_id` bigint(20) NOT NULL AUTO_INCREMENT,
`campaign_id` bigint(20) NOT NULL,				
`contribution_amount` float NOT NULL,
`contribution_amount_is_percentage` tinyint(1) NOT NULL DEFAULT 0,
`date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`is_active` tinyint(1) NOT NULL DEFAULT 1,
PRIMARY KEY (`campaign_benefactor_id`),
KEY `campaign` (`campaign_id`), 
KEY `active` (`is_active`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
EOD;

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}
}	

endif;