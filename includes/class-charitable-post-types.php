<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Post_Types' ) ) : 

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class 		Charitable_Post_Types
 * @version		0.1
 * @package		Charitable/Classes/Core
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Post_Types {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the on_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
	
		add_action( 'init', array( &$this, 'register_post_types' ), 5 );

		// The main Charitable class will save the one instance of this object.
		$this->charitable->register_object( $this );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @see charitable_start hook
	 * 
	 * @param Charitable $charitable 
	 * @return void
	 * @static 
	 * @access public
	 * @since 0.1
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
	 * @see init hook
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 * @return void
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
						'name' 					=> __( 'Campaign', 'charitable' ),
						'singular_name' 		=> __( 'Campaign', 'charitable' ),
						'menu_name'				=> _x( 'Campaign', 'Admin menu name', 'charitable' ),
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
					'supports' 				=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'page-attributes' ),
					'has_archive' 			=> true,
					'show_in_nav_menus' 	=> true
				)
			) 
		);

		/**
		 * Donations post type. 
		 *
		 * To change any of the arguments used for the post type, other than the name
		 * of the post type itself, use the 'charitable_donation_post_type' filter. 
		 */ 
		register_post_type( 'donation', 
			apply_filters( 'charitable_donations_post_type',
				array(
					'labels' => array(
						'name' 					=> __( 'Donation', 'charitable' ),
						'singular_name' 		=> __( 'Donation', 'charitable' ),
						'menu_name'				=> _x( 'Donation', 'Admin menu name', 'charitable' ),
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
					'description' 			=> __( 'This is where you can manually create new donations.', 'charitable' ),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'donation',
					'menu_icon'				=> '',
					'map_meta_cap'			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> false,
					'query_var' 			=> true,
					'supports' 				=> array( 'title' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true
				)
			) 
		);
	}
}

endif; // End class_exists check.