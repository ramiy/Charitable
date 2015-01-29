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
	 * The page to use when registering sections and fields.
	 *
	 * @var 	string 
	 * @access 	private
	 */
	private $admin_menu_parent_page;

	/**
	 * The capability required to view the admin menu. 
	 *
	 * @var 	string
	 * @access  private
	 */
	private $admin_menu_capability;

	/**
	 * Current field. Used to access field args from the views. 	 
	 *
	 * @var 	array
	 * @access  private
	 */
	private $current_field;	

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
	 * Create object instance. 
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->admin_menu_capability 	= apply_filters( 'charitable_admin_menu_capability', 'manage_options' );
		$this->admin_menu_parent_page 	= 'charitable';

		add_action( 'admin_menu', 							array( $this, 'add_menu' ) );
		add_action( 'admin_init', 							array( $this, 'register_settings' ) );		
		do_action( 'charitable_admin_settings_start', 		$this );
	}

	/**
	 * Add Settings menu item under the Campaign menu tab.
	 * 
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add_menu() {

		add_menu_page( 'Charitable', 'Charitable', $this->admin_menu_capability, $this->admin_menu_parent_page, array( $this, 'render_charitable_settings_page' ) );

		add_submenu_page( $this->admin_menu_parent_page, __( 'All Campaigns', 'charitable' ), __( 'Campaigns', 'charitable' ), $this->admin_menu_capability, 'edit.php?post_type=campaign' );
		add_submenu_page( $this->admin_menu_parent_page, __( 'Add Campaign', 'charitable' ), __( 'Add Campaign', 'charitable' ), $this->admin_menu_capability, 'post-new.php?post_type=campaign' );
		add_submenu_page( $this->admin_menu_parent_page, __( 'Donations', 'charitable' ), __( 'Donations', 'charitable' ), $this->admin_menu_capability, 'edit.php?post_type=donation' );
		add_submenu_page( $this->admin_menu_parent_page, __( 'Settings', 'charitable' ), __( 'Settings', 'charitable' ), $this->admin_menu_capability, 'charitable-settings', array( $this, 'render_charitable_settings_page' ) );

		remove_submenu_page( $this->admin_menu_parent_page, $this->admin_menu_parent_page );
	}

	/**
	 * Return the array of tabs used on the settings page.  
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_sections() {
		return apply_filters( 'charitable_settings_tabs', array( 
			'general'	=> __( 'General', 'charitable' ), 
			'gateways' 	=> __( 'Payment Gateways', 'charitable' ), 
			'emails'	=> __( 'Emails', 'charitable' )
		) );
	}

	/**
	 * Return an array with all the fields & sections to be displayed. 
	 *
	 * @uses 	charitable_settings_fields
	 * @see 	Charitable_Admin_Settings::register_setting()
	 *
	 * @return 	array
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function get_fields() {
		return apply_filters( 'charitable_settings_fields', array(
			'general'					=> array(
				'section_dangerous'		=> array(
					'title'				=> __( 'Dangerous Settings', 'charitable' ), 
					'type'				=> 'heading', 
					'priority'			=> 100
				),
				'delete_data_on_uninstall'	=> array(
					'label_for'			=> __( 'Reset Data', 'charitable' ), 
					'type'				=> 'checkbox', 
					'help'				=> __( 'DELETE ALL DATA when uninstalling the plugin.', 'charitable' ), 
					'priority'			=> 105
				)
			),
			'gateways'					=> array(
				'gateways'				=> array(
					'label_for'			=> __( 'Available Payment Gateways', 'charitable' ),
					'callback'			=> array( $this, 'render_gateways_table' ), 
					'priority'			=> 5
				)
			)
		) );
	}

	/**
	 * Return the currently displayed field.  
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_current_field() {
		return $this->current_field;
	}

	/**
	 * Register setting.
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_settings() {
		register_setting( 'charitable_settings', 'charitable_settings', array( $this, 'sanitize_settings' ) );

		$fields = $this->get_fields();

		if ( empty( $fields ) ) {
			return;
		}

		/**
		 * Register each section.
		 */
		foreach ( $this->get_sections() as $section_key => $section ) {
			$section_id = 'charitable_settings_' . $section_key;
			
			add_settings_section(
				$section_id,
				__return_null(), 
				'__return_false', 
				$section_id
			);			

			if ( ! isset( $fields[ $section_key ] ) || empty( $fields[ $section_key ] ) ) {
				continue;
			}

			/**
			 * Sort by priority
			 */
			$section_fields = $fields[ $section_key ];
			uasort( $section_fields, 'charitable_priority_sort' );

			/**
			 * Add the individual fields within the section.
			 */
			foreach ( $section_fields as $key => $field ) {				

				$callback 		= isset( $field[ 'callback' ] ) ? $field[ 'callback' ] : array( $this, 'render_field' );
				$field[ 'key' ] = $key;

				if ( isset( $field[ 'label_for' ] ) ) {
					$label = $field[ 'label_for' ];
				}
				elseif ( isset( $field[ 'title' ] ) ) {
					$label = $field[ 'title' ];
				}
				else {
					$label = ucfirst( $key );
				}			

				add_settings_field( 
					'charitable_settings['. $key .']', 
					$label, 
					$callback, 
					$section_id, 
					$section_id, 
					$field 
				); 
			}
		}
	}	

	/**
	 * Sanitize submitted settings before saving to the database. 
	 *
	 * @param 	array 	$values
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function sanitize_settings( $values ) {
		foreach ( $this->get_fields() as $section ) {
			foreach ( $section as $key => $field ) {
				/**
				 * Checkboxes are either 1 or 0
				 */
				if ( 'checkbox' == $field[ 'type' ] ) {
					$values[$key] = intval( isset( $values[ $key ] ) && 'on' == $values[ $key ] );
				}
			}
		}

		return $values;
	}

	/**
	 * Display the Charitable settings page. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function render_charitable_settings_page() {
		charitable_admin_view( 'settings/settings' );
	}

	/**
	 * Render field. This is the default callback used for all fields, unless an alternative callback has been specified. 
	 *
	 * @param 	array 		$args
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function render_field( $args ) {		
		$this->current_field = $args;

		$field_type = isset( $args[ 'type' ] ) ? $args[ 'type' ] : 'text';

		charitable_admin_view( 'settings/' . $field_type . '-field' );
	}

	/**
	 * Display table with available payment gateways.  
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function render_gateways_table( $args ) {
		$this->current_field = $args;

		charitable_admin_view( 'settings/gateways-table' );
	}
}

endif; // End class_exists check