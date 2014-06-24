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
 * @version		0.0.1
 * @package		WPCharitable/Classes/Core
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
	 * @since 0.0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->wp_charitable = $charitable;
	
		add_action( 'init', array( &$this, 'register_post_types' ), 5 );

		// The main Charitable class will save the one instance of this object.
		$this->wp_charitable->save_start_object( $this );
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
	 * @since 0.0.1
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
	 * @since 0.0.1
	 * @return void
	 */
	public function register_post_types() {
		do_action( 'charitable_regiser_post_type' );
		
		register_post_type( 'cause', 
			apply_filters( 'charitable_cause_post_type',
				array(
					'labels' => array(
						'name' 					=> __( 'Cause', 'charitable' ),
						'singular_name' 		=> __( 'Cause', 'charitable' ),
						'menu_name'				=> _x( 'Cause', 'Admin menu name', 'charitable' ),
						'add_new' 				=> __( 'Add Cause', 'charitable' ),
						'add_new_item' 			=> __( 'Add New Cause', 'charitable' ),
						'edit' 					=> __( 'Edit', 'charitable' ),
						'edit_item' 			=> __( 'Edit Cause', 'charitable' ),
						'new_item' 				=> __( 'New Cause', 'charitable' ),
						'view' 					=> __( 'View Cause', 'charitable' ),
						'view_item' 			=> __( 'View Cause', 'charitable' ),
						'search_items' 			=> __( 'Search Causes', 'charitable' ),
						'not_found' 			=> __( 'No Causes found', 'charitable' ),
						'not_found_in_trash' 	=> __( 'No Causes found in trash', 'charitable' ),
						'parent' 				=> __( 'Parent Cause', 'charitable' )
					),
					'description' 			=> __( 'This is where you can create new causes for people to support.', 'charitable' ),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'cause',
					'map_meta_cap'			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite' 				=> false,
					'query_var' 			=> true,
					'supports' 				=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
					'has_archive' 			=> true,
					'show_in_nav_menus' 	=> true
				)
			) 
		);
	}
}

endif; // End class_exists check.