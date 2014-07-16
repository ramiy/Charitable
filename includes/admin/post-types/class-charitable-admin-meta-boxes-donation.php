<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Admin_Meta_Boxes_Donation' ) ) : 

/**
 * Charitable Admin Meta Boxes Donation.
 *
 * @class 		Charitable_Admin_Meta_Boxes_Donation
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Donation
 * @version     0.0.1
 */
class Charitable_Admin_Meta_Boxes_Donation extends Charitable_Admin_Meta_Boxes {

	/**
	 * Create object instance. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function __construct(Charitable $charitable) {
		add_action('add_meta_boxes', array( &$this, 'add_meta_boxes' ));
		add_action('save_post', array( &$this, 'save_post' ), 10, 2);
	}

	/**
	 * Create an object instance. This will only work during the charitable_admin_start event.
	 * 
	 * @see charitable_admin_start hook
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	public static function charitable_admin_start(Charitable $charitable) {
		if ( ! $charitable->is_admin_start() ) {
			return;
		}

		new Charitable_Admin_Meta_Boxes_Donation($charitable);
	}

	/**
	 * Add meta boxes.
	 * 
	 * @see add_meta_boxes hook
	 *
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function add_meta_boxes() {
		
	}

	/**
	 * Save meta for the donation. 
	 * 
	 * @param int $post_ID Post ID.
	 * @param WP_Post $post Post object.
	 * @return void
	 * @access public 
	 * @since 0.0.1
	 */
	public function save_post($post_id, WP_Post $post) {
		
	}	
}

endif; // End class_exists check