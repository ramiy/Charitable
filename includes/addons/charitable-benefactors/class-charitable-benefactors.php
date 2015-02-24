<?php
/**
 * Main class for setting up the Charitable Benefactors Addon, which is programatically activated by child themes.
 *
 * @package		Charitable/Classes/Charitable_Benefactors
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Benefactors' ) ) : 

/**
 * Charitable_Benefactors
 *
 * @since 		1.0.0
 */
class Charitable_Benefactors implements Charitable_Addon_Interface {

	/**
	 * Responsible for creating class instances. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function load() {
		new Charitable_Benefactors();				
	}

	/**
	 * Create class instance. 
	 *
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {		
		$this->load_dependencies();
		$this->attach_hooks_and_filters();
	}

	/**
	 * Include required files. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function load_dependencies() {
		require_once( 'class-charitable-benefactor.php' );
		require_once( 'class-charitable-benefactors-db.php' );
	}

	/**
	 * Set up hooks and filter. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {
		add_filter( 'charitable_db_tables', 					array( $this, 'register_table' ) );
		add_action( 'charitable_campaign_benefactor_meta_box', 	array( $this, 'benefactor_meta_box' ) );
		add_action( 'wp_ajax_charitable-open-benefactor-form', 	array( $this, 'benefactor_form' ) );
		add_action( 'charitable_uninstall', 					array( $this, 'uninstall' ) );
	}

	/**
	 * Register table. 
	 *
	 * @param 	array 		$tables
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function register_table( $tables ) {
		$tables['benefactors'] = 'Charitable_Benefactors_DB';
		return $tables;
	}

	/**
	 * Display a benefactor relationship block inside of a meta box on campaign pages. 
	 *
	 * @param 	Object 		$benefactor
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function benefactor_meta_box( $benefactor ) {	
		charitable_admin_view( 'metaboxes/campaign-benefactors/summary', array( 'benefactor' => $benefactor ) );		
	}

	/**
	 * Display benefactor relationship form.  
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function benefactor_form() {
		$data = isset( $_POST['data'] ) ? $_POST['data'] : array();

		charitable_admin_view( 'metaboxes/campaign-benefactors/form', $data );

		wp_die();
	}

	/**
	 * Called when Charitable is uninstalled and data removal is set to true.  
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function uninstall() {
		if ( 'charitable_uninstall' != current_filter() ) {
			return;
		}
		
		global $wpdb;		

		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "charitable_benefactors" );

		delete_option( $wpdb->prefix . 'charitable_benefactors_db_version' );
	}

	/**
	 * Activate the addon. 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function activate() {		
		
		/* This method should only be called on the charitable_activate_addon hook */
		if ( 'charitable_activate_addon' !== current_filter() ) {
			return false;
		}

		new Charitable_Benefactors();

		self::load();

		$table = new Charitable_Benefactors_DB();
		@$table->create_table();
	}	
}

endif; // End class_exists check