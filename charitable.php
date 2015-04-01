<?php
/**
 * Plugin Name: 		Charitable
 * Plugin URI: 			http://wpcharitable.com
 * Description: 		Fundraise with WordPress.
 * Version: 			1.0.0~alpha-1.0
 * Author: 				Studio 164a
 * Author URI: 			https://164a.com
 * Requires at least: 	4.0
 * Tested up to: 		4.1
 *
 * Text Domain: 		charitable
 * Domain Path: 		/i18n/languages/
 *
 * @package 			Charitable
 * @author 				Eric Daams
 * @copyright 			Copyright (c) 2014, Studio 164a
 * @license    			http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable' ) ) :

/**
 * Main Charitable class
 *
 * @class 		Charitable
 * @version		1.0.0
 */
class Charitable {

	/**
     * @var 	string
     */
	const VERSION = '1.0.0';

	/**
     * @var 	string 			A date in the format: YYYYMMDD
     */
    const DB_VERSION = '20141024';	

	/**
	 * @var 	Charitable
	 * @access  private
	 */
	private static $instance = null;

	/**
     * @var 	string
     * @access  private
     */
    private $textdomain = 'charitable';    

    /**
     * @var 	string 		Directory path for the plugin.
     * @access  private
     */
    private $directory_path;

    /**
     * @var 	string 		Directory url for the plugin.
     * @access  private
     */
    private $directory_url;

    /**
     * @var 	string 		Directory path for the includes folder of the plugin.
     * @access  private
     */
    private $includes_path;    

    /**
     * @var 	string 		Directory path for the admin folder of the plugin. 
     * @access  private
     */
    private $admin_path;

    /**
     * @var 	string 		Directory path for the assets folder. 
     * @access  private
     */
    private $assets_path;

	/**
     * @var 	string 		Directory path for the templates folder in themes.
     * @access  private
     */
    private $theme_template_path;    

	/**
     * @var 	string 		Directory path for the templates folder the plugin.
     * @access  private
     */
    private $plugin_template_path;        

    /**
     * @var 	array 		Store of registered objects.  
     * @access  private
     */
    private $registry;

    /**
     * Create class instance. 
     * 
     * @return 	void
     * @since 	1.0.0
     */
	public function __construct() {
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url = plugin_dir_url( __FILE__ );
		$this->includes_path = $this->directory_path . 'includes/';
		$this->start();
	}

	/**
	 * Returns the original instance of this class. 
	 * 
	 * @return 	Charitable
	 * @since 	1.0.0
	 */
	public static function get_instance() {
		return self::$instance;
	}

	/**
	 * Run the startup sequence. 
	 *
	 * This is only ever executed once.  
	 * 
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function start() {
		// If we've already started (i.e. run this function once before), do not pass go. 
		if ( $this->started() ) {
			return;
		}

		// Set static instance
        self::$instance = $this;

        $this->load_dependencies();        

        $this->maybe_upgrade();

        $this->attach_hooks_and_filters();

        $this->maybe_start_admin();      

        $this->maybe_start_public();

		// Hook in here to do something when the plugin is first loaded.
		do_action('charitable_start', $this);
	}

	/**
	 * Include necessary files.
	 * 
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function load_dependencies() {
		/**
		 * Start objects.
		 */
		require_once( $this->includes_path . 'class-charitable-start-object.php' );
		require_once( $this->includes_path . 'class-charitable-addons.php' );
		require_once( $this->includes_path . 'class-charitable-roles.php' );
		require_once( $this->includes_path . 'class-charitable-donation-actions.php' );
		require_once( $this->includes_path . 'class-charitable-post-types.php' );
		require_once( $this->includes_path . 'class-charitable-campaign-query.php' );
		require_once( $this->includes_path . 'class-charitable-widgets.php' );
		require_once( $this->includes_path . 'class-charitable-gateway.php' );

		/**
		 * Interfaces
		 */
		require_once( $this->includes_path . 'interface-charitable-donation-form.php' );

		/**
		 * Models.
		 */
		require_once( $this->includes_path . 'class-charitable-form.php' );
		require_once( $this->includes_path . 'class-charitable-campaign.php' );
		require_once( $this->includes_path . 'class-charitable-donation.php' );
		require_once( $this->includes_path . 'class-charitable-donation-form.php' );
		require_once( $this->includes_path . 'class-charitable-donation-form-hidden.php' );
		require_once( $this->includes_path . 'class-charitable-donations.php' );
		require_once( $this->includes_path . 'class-charitable-donor.php' );
		require_once( $this->includes_path . 'class-charitable-session-donation.php' );

		require_once( $this->includes_path . 'db/abstract-class-charitable-db.php' );
		require_once( $this->includes_path . 'db/class-charitable-campaign-donations-db.php' );

		/**
		 * Helpers.
		 */
		require_once( $this->includes_path . 'class-charitable-currency.php' );
		require_once( $this->includes_path . 'class-charitable-request.php' );		
		require_once( $this->includes_path . 'class-charitable-locations.php' );
		require_once( $this->includes_path . 'class-charitable-notices.php' );

		/**
		 * Functions.
		 */
		require_once( $this->includes_path . 'charitable-core-functions.php' );
		require_once( $this->includes_path . 'charitable-utility-functions.php' );
	}

	/**
	 * Set up hook and filter callback functions.
	 * 
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {				
		register_activation_hook( 	__FILE__, array( $this, 'activate') );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate') );

		add_action('charitable_start',	array( 'Charitable_Donation_Actions', 'charitable_start' ), 3 );
		add_action('charitable_start',	array( 'Charitable_Post_Types', 'charitable_start' ), 3 );		
		add_action('charitable_start',	array( 'Charitable_Widgets', 'charitable_start' ), 3 );
		add_action('charitable_start',	array( 'Charitable_Gateway', 'charitable_start' ), 3 ); 
		add_action('charitable_start', 	array( 'Charitable_Addons', 'charitable_start' ), 3 );		
		add_action('charitable_start', 	array( 'Charitable_Request', 'charitable_start' ), 3 );
	}

	/**
	 * Checks whether we're in the admin area and if so, loads the admin-only functionality.
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function maybe_start_admin() {
		if ( ! is_admin() ) {
			return;
		}

		require_once( $this->get_path( 'admin' ) . 'class-charitable-admin.php' );

		add_action('charitable_start', array( 'Charitable_Admin', 'charitable_start' ), 3 );
	}

	/**
	 * Checks whether we're on the public-facing side and if so, loads the public-facing functionality.
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function maybe_start_public() {
		if ( is_admin() ) {
			return;
		}

		require_once( $this->get_path( 'public' ) . 'class-charitable-public.php' );

		add_action('charitable_start', array( 'Charitable_Public', 'charitable_start' ), 3 );
	}

	/**
	 * Returns whether we are currently in the start phase of the plugin. 
	 *
	 * @return 	bool
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function is_start() {
		return current_filter() == 'charitable_start';
	}

	/**
	 * Returns whether the plugin has already started.
	 * 
	 * @return 	bool
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function started() {
		return did_action( 'charitable_start' ) || current_filter() == 'charitable_start';
	}

	/**
	 * Returns whether the plugin is being activated. 
	 *
	 * @return 	bool
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function is_activation() {
		return current_filter() == 'activate_charitable/charitable.php';
	}

	/**
	 * Returns whether the plugin is being deactivated.
	 *
	 * @return 	bool
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function is_deactivation() {
		return current_filter() == 'deactivate_charitable/charitable.php';
	}

	/**
	 * Stores an object in the plugin's registry.
	 *
	 * @param 	mixed $object
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_object($object) {
		if ( ! is_object( $object ) ) {
			return;
		}

		$class = get_class( $object );

		$this->registry[$class] = $object;
	}

	/**
	 * Returns a registered object.
	 * 
	 * @param 	string $class The type of class you want to retrieve.
	 * @return 	mixed The object if its registered. Otherwise false.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_registered_object($class) {
		return isset( $this->registry[$class] ) ? $this->registry[$class] : false;
	}

	/**
	 * Returns plugin paths. 
	 *
	 * @param 	string $path 			// If empty, returns the path to the plugin.
	 * @param 	bool $absolute_path 	// If true, returns the file system path. If false, returns it as a URL.
	 * @return 	string
	 * @since 	1.0.0
	 */
	public function get_path($type = '', $absolute_path = true ) {		
		$base = $absolute_path ? $this->directory_path : $this->directory_url;

		switch( $type ) {
			case 'includes' : 
				$path = $base . 'includes/';
				break;

			case 'admin' :
				$path = $base . 'admin/';
				break;

			case 'public' : 
				$path = $base . 'public/';
				break;

			case 'directory' : 
				$path = $base;
				break;

			default :
				$path = __FILE__;
		}

		return $path;
	}

	/**
	 * Returns the plugin's version number. 
	 *
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_version() {
		return self::VERSION;
	}

	/**
	 * Returns the public class. 
	 *
	 * @return 	Charitable_Public
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_public() {
		return $this->get_registered_object( 'Charitable_Public' );
	}

	/**
	 * Returns the admin class. 
	 *
	 * @return 	Charitable_Admin
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_admin() {
		return $this->get_registered_object( 'Charitable_Admin' );
	}

	/**
	 * Returns the location helper. 
	 *
	 * @return 	Charitable_Locations
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_location_helper() {
		$location_helper = $this->get_registered_object('Charitable_Locations');

		if ( false === $location_helper ) {
			$location_helper = new Charitable_Locations();
			$this->register_object( $location_helper );
		}

		return $location_helper;
	}

	/**
	 * Return the current request object. 
	 *
	 * @return 	Charitable_Request
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_request() {
		$request = $this->get_registered_object('Charitable_Request');

		if ( $request === false ) {
			$request = new Charitable_Request();
			$this->register_object( $request );
		}

		return $request;
	}

	/**
	 * Return an instance of the currency helper. 
	 *
	 * @return 	Charitable_Currency
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_currency_helper() {
		$currency_helper = $this->get_registered_object('Charitable_Currency');

		if ( false === $currency_helper ) {
			$currency_helper = new Charitable_Currency();
			$this->register_object( $currency_helper );
		}

		return $currency_helper;
	}

	/**
	 * Returns the model for one of Charitable's database tables. 
	 *
	 * @param 	string 			$table
	 * @return 	Charitable_DB
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_db_table( $table ) {
		$default_tables = array(
			'campaign_donations'	=> 'Charitable_Campaign_Donations_DB'
		);
		
		$tables = apply_filters( 'charitable_db_tables', $default_tables );

		if ( isset( $tables[ $table ] ) ) {
			$class_name = $tables[ $table ];
		}
		elseif ( in_array( $table, $tables ) ) {
			$class_name = $table;
		}
		else {
			_doing_it_wrong( __METHOD__, sprintf( 'Invalid table %s passed', $table ), '1.0.0' );
			return null;
		}


		$db_table = $this->get_registered_object( $class_name );

		if ( false === $db_table ) {
			$db_table = new $class_name;
			$this->register_object( $db_table );
		}

		return $db_table;
	}

	/**
	 * Runs on plugin activation. 
	 *
	 * @see register_activation_hook
	 *
	 * @return 	void
	 * @access 	public
	 * @since	1.0.0
	 */
	public function activate() {
		require_once( $this->get_path( 'includes' ) . 'class-charitable-install.php' );
		new Charitable_Install( $this );
	}

	/**
	 * Runs on plugin deactivation. 
	 *
	 * @see 	register_deactivation_hook
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function deactivate() {
		require_once( $this->get_path( 'includes' ) . 'class-charitable-uninstall.php' );
		new Charitable_Uninstall( $this );
	}

	/**
	 * Perform upgrade routine if necessary. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function maybe_upgrade() {
		$db_version = get_option( 'charitable_version' );

		if ( $db_version !== self::VERSION ) {		

			require_once( $this->get_path( 'includes' ) . 'class-charitable-upgrade.php' );

			Charitable_Upgrade::upgrade_from( $db_version, self::VERSION );
		}
	}

	/**
	 * Throw error on object clone. 
	 *
	 * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class. 
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable' ), '1.0.0' );
	}	
}

$charitable = new Charitable();

endif; // End if class_exists check