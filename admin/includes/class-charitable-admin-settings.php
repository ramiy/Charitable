<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Admin_Settings' ) ) : 

/**
 * Charitable Settings Pages.
 *
 * @class 		Charitable_Admin_Settings
 * @abstract
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Settings
 * @version     1.0.0
 */
final class Charitable_Admin_Settings {

	/**
	 * @var 	Charitable $charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * @var 	string $page The page to use when registering sections and fields.
	 * @access 	private
	 */
	private $page;

	/**
	 * Create object instance. 
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->page = 'charitable';

		add_action('admin_menu', array(&$this, 'add_menu'));
		add_action('admin_init', array(&$this, 'add_sections'));
		add_action('admin_init', array(&$this, 'add_fields'));
		add_action('admin_init', array(&$this, 'register_setting'));
	}

	/**
	 * Create an object instance. This will only work during the charitable_start event.
	 * 
	 * @see charitable_start hook
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Admin_Settings($charitable);
	}

	/**
	 * Add Settings menu item under the Campaign menu tab.
	 * 
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add_menu() {
		//add_submenu_page();
	}

	/**
	 * Return an array with all the fields & sections to be displayed. 
	 *
	 * @uses charitable_settings_fields
	 *
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_fields() {
		return apply_filters( 'charitable_settings_fields', array(

		) );
	}

	/**
	 * Add sections to the settings page.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add_sections() {

	}

	/**
	 * Add fields to the settings page.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add_fields() {

	}

	/**
	 * Register setting.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_setting() {

	}

	/**
	 * Render field. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function render() {
		
	}
}

endif; // End class_exists check