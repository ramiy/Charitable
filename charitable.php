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
    private $theme_version = '0.0.1';

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
     * Create class instance. 
     * 
     * @return void
     * @since 0.0.1
     */
	public function __construct() {
		$this->directory_path = plugin_dir_path( __FILE__ );
		$this->directory_url = plugin_dir_url( __FILE__ );
		$this->start();
	}

	/**
	 * Returns the original instance of this class. 
	 * 
	 * @return Charitable
	 * @since 0.0.1
	 */
	public function get_instance() {
		return self::$instance;
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
		if ( ! $this->started() ) {
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
		require_once( $this->directory_path . 'includes/class-charitable-post-types.php' );
	}

	/**
	 * Set up hook and filter callback functions.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function attach_hooks_and_filters() {
		add_action('charitable_start', array('Charitable_Post_Types', 'charitable_start'));
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

		$this->include_admin_files();

		$this->attach_admin_hooks_and_filters();

		do_action('charitable_admin_start', $this);
	}

	/**
	 * Include admin-only files.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function include_admin_files() {
		require_once( $this->directory_path . 'includes/admin/class-charitable-admin.php' );
	}

	/**
	 * Sets up hook and filter callback functions for admin-only functionality.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function attach_admin_hooks_and_filters() {
		add_action('charitable_admin_start', array('Charitable_Admin', 'charitable_admin_start'));
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
		return current_filter() == 'charitable_admin_start';
	}

	/**
	 * Retuns whether the plugin has already started.
	 * 
	 * @return bool
	 * @access public
	 * @since 0.0.1
	 */
	public function started() {
		return ! did_action('charitable_start') && current_filter() != 'charitable_start';
	}

	/**
	 * Save an object to the plugin object registry. 
	 *
	 * @param mixed $object
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function save_start_object($object) {
		if ( ! is_object( $object ) ) {
			return;
		}

		$class = get_class( $object );

		$this->start_objects[$class] = $object;	
	}

	/**
	 * Returns a start object. 
	 * 
	 * @param string $class The type of class you want to retrieve.
	 * @return mixed The object if its registered. Otherwise null.
	 * @access public
	 * @since 0.0.1
	 */
	public function get_start_object($class) {
		return isset( $this->start_objects[$class] ) ? $this->start_objects[$class] : null;
	}
}

endif; // End if class_exists check

$charitable = new Charitable();

/**
 * This returns the original Charitable object. 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return Charitable
 * @since 0.0.1
 */
function get_charitable() {
    global $charitable;    
    return $charitable::get_instance();
}