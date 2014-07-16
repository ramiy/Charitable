<?php
/**
 * Plugin Name: Charitable
 * Plugin URI: http://164a.com
 * Description: 
 * Version: 0.0.1
 * Author: Studio 164a
 * Author URI: http://164a.com
 * Requires at least: 3.9
 * Tested up to: 3.9
 *
 * Text Domain: charitable
 * Domain Path: /languages/
 *
 * @package Charitable
 * @category Core
 * @author Studio164a
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable' ) ) :

/**
 * Main Charitable class
 *
 * @class Charitable
 * @version	0.0.1
 */
final class Charitable {

	/**
	 * @var Charitable
	 */
	private static $instance = null;

	/**
     * @var string
     */
    private $textdomain = 'charitable';

    /**
     * @var string
     */
    private $version = '0.0.1';

    /**
     * @var array Registry of the start objects.
     */
    private $start_objects = array();

    /**
     * @var string Directory path for the plugin.
     */
    private $directory_path;

    /**
     * @var string Directory url for the plugin.
     */
    private $directory_url;

    /**
     * @var string Directory path for the includes folder of the plugin.
     */
    private $includes_path;    

    /**
     * @var string Directory path for the admin folder of the plugin. 
     */
    private $admin_path;

    /**
     * @var string Directory path for the assets folder. 
     */
    private $assets_path;

	/**
     * @var string Directory path for the templates folder in themes.
     */
    private $theme_template_path;    

	/**
     * @var string Directory path for the templates folder the plugin.
     */
    private $plugin_template_path;        

    /**
     * Create class instance. 
     * 
     * @return void
     * @since 0.0.1
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
	 * @return Charitable
	 * @since 0.0.1
	 */
	public static function get_instance() {
		return self::$instance;
	}

	/**
	 * Throw error on object clone. 
	 *
	 * This class is specifically designed to be instantiated once. You can retrieve the instance using get_charitable()
	 *
	 * @since 0.0.1
	 * @access public
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable' ), '0.0.1' );
	}

	/**
	 * Disable unserializing of the class. 
	 *
	 * @since 0.0.1
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable' ), '0.0.1' );
	}

	/**
	 * Run the startup sequence. 
	 *
	 * This is only ever executed once.  
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function start() {
		// If we've already started (i.e. run this function once before), do not pass go. 
		if ( $this->started() ) {
			return;
		}

		// Set static instance
        self::$instance = $this;

        $this->include_files();

        $this->attach_hooks_and_filters();

        $this->maybe_start_admin();        

		// Hook in here to do something when the plugin is first loaded.
		do_action('charitable_start', $this);
	}

	/**
	 * Include necessary files.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function include_files() {
		/**
		 * Start objects.
		 */
		require_once( $this->includes_path . 'class-charitable-donation-controller.php' );
		require_once( $this->includes_path . 'class-charitable-post-types.php' );
		require_once( $this->includes_path . 'class-charitable-query.php' );
		require_once( $this->includes_path . 'class-charitable-templates.php' );
		require_once( $this->includes_path . 'class-charitable-widgets.php' );

		/**
		 * Interfaces.
		 */
		require_once( $this->includes_path . 'interface-charitable-donation-form.php' );

		/**
		 * Models.
		 */
		require_once( $this->includes_path . 'class-charitable-campaign.php' );
		require_once( $this->includes_path . 'class-charitable-donation.php' );
		require_once( $this->includes_path . 'class-charitable-donation-form.php' );
		require_once( $this->includes_path . 'class-charitable-donation-form-hidden.php' );

		/**
		 * Helpers.
		 */
		require_once( $this->includes_path . 'class-charitable-template.php' );
		require_once( $this->includes_path . 'class-charitable-template-part.php' );
		require_once( $this->includes_path . 'class-charitable-location-helper.php' );
	}

	/**
	 * Set up hook and filter callback functions.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function attach_hooks_and_filters() {				
		add_action('charitable_start', array( 'Charitable_Donation_Controller', 'charitable_start' ) );
		add_action('charitable_start', array( 'Charitable_Post_Types', 'charitable_start' ) );
		add_action('charitable_start', array( 'Charitable_Query', 'charitable_start' ) );
		add_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ) );
		add_action('charitable_start', array( 'Charitable_Widgets', 'charitable_start' ) );
	}

	/**
	 * Checks whether we're in the admin area and if so, loads the admin-only functionality.
	 *
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function maybe_start_admin() {
		if ( ! is_admin() ) {
			return;
		}

		require_once( $this->includes_path . 'admin/class-charitable-admin.php' );

		Charitable_Admin::start($this);
	}

	/**
	 * Returns whether we are currently in the start phase of the plugin. 
	 *
	 * @return bool
	 * @access public
	 * @since 0.0.1
	 */
	public function is_start() {
		return current_filter() == 'charitable_start';
	}

	/**
	 * Returns whether we are currently in the admin start phase of the plugin. 
	 *
	 * @return bool
	 * @access public
	 * @since 0.0.1
	 */
	public function is_admin_start() {
		$charitable_admin = $this->get_registered_object('Charitable_Admin');
		return ! is_null( $charitable_admin ) && $charitable_admin->is_admin_start();
	}

	/**
	 * Returns whether the plugin has already started.
	 * 
	 * @return bool
	 * @access public
	 * @since 0.0.1
	 */
	public function started() {
		return did_action( 'charitable_start' ) || current_filter() == 'charitable_start';
	}

	/**
	 * Stores an object in the plugin's registry.
	 *
	 * @param mixed $object
	 * @return void
	 * @access public
	 * @since 0.0.1
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
	 * @param string $class The type of class you want to retrieve.
	 * @return mixed The object if its registered. Otherwise false.
	 * @access public
	 * @since 0.0.1
	 */
	public function get_registered_object($class) {
		return isset( $this->registry[$class] ) ? $this->registry[$class] : false;
	}

	/**
	 * Returns the path to the plugin directory. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_directory_path() {
		return $this->directory_path;
	}

	/**
	 * Returns the URL for the plugin directory. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_directory_url() {
		return $this->directory_url;
	}	

	/**
	 * Returns the path to the includes folder. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_includes_path() {
		return $this->includes_path;
	}

	/**
	 * Returns the path to the admin folder. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_admin_path() {
		if ( ! isset( $this->admin_path ) ) {
			$this->admin_path = $this->includes_path . 'admin/';
		}

		return $this->admin_path;
	}

	/**
	 * Returns the path to the assets folder. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_assets_path() {
		if ( ! isset( $this->assets_path ) ) {
			$this->assets_path = $this->directory_url . 'assets/';
		}

		return $this->assets_path;
	}

	/**
	 * Returns the theme template path. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_theme_template_path() {
		if ( ! isset( $this->theme_template_path ) ) {
			$this->theme_template_path = apply_filters( 'charitable_theme_template_path', 'charitable' );						
		}

		return $this->theme_template_path;
	}

	/**
	 * Returns the plugin template path. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_plugin_template_path() {
		if ( ! isset( $this->plugin_template_path ) ) {
			$this->plugin_template_path = $this->directory_path . 'templates/';
		}

		return $this->plugin_template_path;
	}	

	/**
	 * Returns the plugin's version number. 
	 *
	 * @return string
	 * @access public
	 * @since 0.0.1
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns the location helper. 
	 *
	 * @return Charitable_Location_Helper
	 * @access public
	 * @since 0.0.1
	 */
	public function get_location_helper() {
		$location_helper = $this->get_registered_object('Charitable_Location_Helper');

		if ( $location_helper === false ) {
			$location_helper = new Charitable_Location_Helper();
			$this->register_object( $location_helper );
		}

		return $location_helper;
	}
}

endif; // End if class_exists check

$charitable = new Charitable();

/**
 * This returns the original Charitable object (created just above). 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return Charitable
 * @since 0.0.1
 */
function get_charitable() {
    return Charitable::get_instance();
}