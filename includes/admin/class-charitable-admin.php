<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Admin' ) ) : 

/**
 * Charitable Admin.
 *
 * @class 		Charitable_Admin 
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin
 * @version     0.0.1
 */
final class Charitable_Admin {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_admin_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->include_files();

		$this->attach_hooks_and_filters();

		do_action('charitable_admin_start', $this->charitable, $this);
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 * 
	 * @param Charitable $charitable 
	 * @return void
	 * @static 
	 * @access public
	 * @since 0.0.1
	 */
	public static function start(Charitable $charitable) {
		if ( $charitable->started() ) {
			return;
		}

		new Charitable_Admin( $charitable );
	}

	/**
	 * Include admin-only files.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function include_files() {
		require_once( $this->charitable->get_admin_path() . 'charitable-core-admin-functions.php' );
		
		require_once( $this->charitable->get_admin_path() . 'post-types/class-charitable-meta-box-helper.php' );
		require_once( $this->charitable->get_admin_path() . 'post-types/class-charitable-campaign-post-type.php' );
		// require_once( $this->charitable->get_admin_path() . 'post-types/class-charitable-admin-meta-boxes-campaign.php' );
		// require_once( $this->charitable->get_admin_path() . 'post-types/class-charitable-admin-meta-boxes-donation.php' );
	}

	/**
	 * Sets up hook and filter callback functions for admin-only functionality.
	 * 
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function attach_hooks_and_filters() {
		add_action('charitable_admin_start', array('Charitable_Campaign_Post_Type', 'charitable_admin_start'));
		// add_action('charitable_admin_start', array('Charitable_Admin_Meta_Boxes_Campaign', 'charitable_admin_start'));
		// add_action('charitable_admin_start', array('Charitable_Admin_Meta_Boxes_Donation', 'charitable_admin_start'));

		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
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
	 * Loads admin-only scripts and stylesheets. 
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function admin_enqueue_scripts() {			
		/**
		 * Menu styles are loaded everywhere in the Wordpress dashboard. 
		 */
		wp_register_style( 'charitable-admin-menu', $this->charitable->get_assets_path() . 'css/charitable-admin-menu.css', array(), $this->charitable->get_version() );
		wp_enqueue_style( 'charitable-admin-menu' );

		/**
		 * The following styles are only loaded on Charitable screens.
		 */
		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->get_charitable_screens() ) ) {		
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array(), $this->charitable->get_version() );

			wp_register_style( 'charitable-admin', $this->charitable->get_assets_path() . 'css/charitable-admin.css', array(), $this->charitable->get_version() );
			wp_enqueue_style( 'charitable-admin' );

			wp_register_script( 'charitable-admin', $this->charitable->get_assets_path() . 'js/charitable-admin.js', array('jquery-ui-datepicker'), $this->charitable->get_version() );		
			wp_enqueue_script( 'charitable-admin' );
		}
	}

	/**
	 * Returns an array of screen IDs where the Charitable scripts should be loaded. 
	 *
	 * @uses charitable_admin_screens
	 * 
	 * @return array
	 * @access private
	 * @since 0.0.1
	 */
	private function get_charitable_screens() {
		return apply_filters( 'charitable_admin_screens', array(
			'campaign', 
			'donations'
		) );
	}
}

endif;