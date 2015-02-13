<?php
/**
 * The class that defines Charitable's custom post types, taxonomies and post statuses.
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Core
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Post_Types' ) ) : 

/**
 * Charitable_Post_Types
 *
 * @since		1.0.0
 */
final class Charitable_Post_Types {

	/**
	 * @var 	Charitable 		$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the on_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param 	Charitable 		$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
	
		add_action( 'init', array( $this, 'register_post_types' ), 5 );
		add_action( 'init', array( $this, 'register_post_statuses' ), 5 );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @hook 	charitable_start
	 * @param 	Charitable 		$charitable 
	 * @return 	void
	 * @static 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Post_Types( $charitable );
	}

	/**
	 * Register plugin post types. 
	 *
	 * @hook 	init
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_post_types() {
		/**
		 * Campaign post type. 
		 *
		 * To change any of the arguments used for the post type, other than the name
		 * of the post type itself, use the 'charitable_campaign_post_type' filter. 
		 */ 
		register_post_type( 'campaign', 
			apply_filters( 'charitable_campaign_post_type',
				array(
					'labels' => array(
						'name' 					=> __( 'Campaigns', 'charitable' ),
						'singular_name' 		=> __( 'Campaign', 'charitable' ),
						'menu_name'				=> _x( 'Campaigns', 'Admin menu name', 'charitable' ),
						'add_new' 				=> __( 'Add Campaign', 'charitable' ),
						'add_new_item' 			=> __( 'Add New Campaign', 'charitable' ),
						'edit' 					=> __( 'Edit', 'charitable' ),
						'edit_item' 			=> __( 'Edit Campaign', 'charitable' ),
						'new_item' 				=> __( 'New Campaign', 'charitable' ),
						'view' 					=> __( 'View Campaign', 'charitable' ),
						'view_item' 			=> __( 'View Campaign', 'charitable' ),
						'search_items' 			=> __( 'Search Campaigns', 'charitable' ),
						'not_found' 			=> __( 'No Campaigns found', 'charitable' ),
						'not_found_in_trash' 	=> __( 'No Campaigns found in trash', 'charitable' ),
						'parent' 				=> __( 'Parent Campaign', 'charitable' )
					),
					'description' 			=> __( 'This is where you can create new campaigns for people to support.', 'charitable' ),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'campaign',
					'menu_icon'				=> '',
					'map_meta_cap'			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite' 				=> false,
					'query_var' 			=> true,
					'supports' 				=> array( 'title', 'thumbnail' ),
					'has_archive' 			=> true,
					'show_in_nav_menus' 	=> true, 
					'show_in_menu'			=> false
				)
			) 
		);

		/**
		 * Donation post type. 
		 *
		 * To change any of the arguments used for the post type, other than the name
		 * of the post type itself, use the 'charitable_donation_post_type' filter. 
		 */ 
		register_post_type( 'donation', 
			apply_filters( 'charitable_donation_post_type',
				array(
					'labels' => array(
						'name' 					=> __( 'Donations', 'charitable' ),
						'singular_name' 		=> __( 'Donation', 'charitable' ),
						'menu_name'				=> _x( 'Donations', 'Admin menu name', 'charitable' ),
						'add_new' 				=> __( 'Add Donation', 'charitable' ),
						'add_new_item' 			=> __( 'Add New Donation', 'charitable' ),
						'edit' 					=> __( 'Edit', 'charitable' ),
						'edit_item' 			=> __( 'Edit Donation', 'charitable' ),
						'new_item' 				=> __( 'New Donation', 'charitable' ),
						'view' 					=> __( 'View Donation', 'charitable' ),
						'view_item' 			=> __( 'View Donation', 'charitable' ),
						'search_items' 			=> __( 'Search Donations', 'charitable' ),
						'not_found' 			=> __( 'No Donations found', 'charitable' ),
						'not_found_in_trash' 	=> __( 'No Donations found in trash', 'charitable' ),
						'parent' 				=> __( 'Parent Donation', 'charitable' )
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'donation',
					'menu_icon'				=> '',
					'map_meta_cap'			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite' 				=> false,
					'query_var' 			=> false,
					'supports' 				=> array( 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false, 
					'show_in_menu'			=> false
				)
			) 
		);
	}

	/**
	 * Register custom post statuses. 
	 *
	 * @hook 	init
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function register_post_statuses() {
		/**
		 * Post statuses for donations.
		 */
		register_post_status( 'charitable-pending', array(
			'label'                     => _x( 'charitable-pending', 'Status General Name', 'charitable' ),
			'label_count'               => _n_noop( 'Pending (%s)',  'Pending (%s)', 'charitable' ), 
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		) );

		register_post_status( 'charitable-completed', array(
			'label'                     => _x( 'charitable-completed', 'Status General Name', 'charitable' ),
			'label_count'               => _n_noop( 'Completed (%s)',  'Completed (%s)', 'charitable' ), 
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		) );

		register_post_status( 'charitable-failed', array(
			'label'                     => _x( 'charitable-failed', 'Status General Name', 'charitable' ),
			'label_count'               => _n_noop( 'Failed (%s)',  'Failed (%s)', 'charitable' ), 
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		) );

		register_post_status( 'charitable-cancelled', array(
			'label'                     => _x( 'charitable-cancelled', 'Status General Name', 'charitable' ),
			'label_count'               => _n_noop( 'Cancelled (%s)',  'Cancelled (%s)', 'charitable' ), 
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		) );

		register_post_status( 'charitable-refunded', array(
			'label'                     => _x( 'charitable-refunded', 'Status General Name', 'charitable' ),
			'label_count'               => _n_noop( 'Refunded (%s)',  'Refunded (%s)', 'charitable' ), 
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		) );
	}
}

endif; // End class_exists check.