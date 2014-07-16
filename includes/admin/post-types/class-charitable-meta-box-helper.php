<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Meta_Box_Helper' ) ) : 

/**
 * Charitable Meta Box Helper
 *
 * @class 		Charitable_Meta_Box_Helper
 * @abstract
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Meta Boxes
 * @version     0.0.1
 */
class Charitable_Meta_Box_Helper {

	/**
	 * @var string Nonce action.
	 * @access protected
	 */
	protected $nonce_action;

	/**
	 * @var string Nonce name. 
	 * @access protected
	 */
	protected $nonce_name = '_charitable_nonce';

	/**
	 * @var boolean Whether nonce has been added. 
	 * @access protected
	 */
	protected $nonce_added = false;

	/**
	 * Create a helper instance. 
	 *
	 * @param string $nonce_action 
	 * @return void
	 * @since 0.0.1
	 */
	public function __construct( $nonce_action = 'charitable' ) {
		$this->nonce_action = $nonce_action;
	} 

	/**
	 * Meta box callback wrapper. 
	 *
	 * Every meta box is registered with this method as its callback, 
	 * and then delegates to the appropriate view. 
	 *
	 * @see Charitable_Meta_Box_Helper::add_meta_boxes()
	 * 
	 * @param WP_Post $post The post object.
	 * @param array $args The arguments passed to the meta box, including the view to render. 
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function display(WP_Post $post, array $args) {
		
		if ( ! isset( $args['args']['view'] ) ) {
			return;
		}

		/**
		 * Set the nonce.
		 */
		if ( $this->nonce_added === false ) {

			wp_nonce_field( $this->nonce_action, $this->nonce_name );

			$this->nonce_added = true;
		}

		do_action('charitable_meta_box_before');

		charitable_admin_view( $args['args']['view'] );

		do_action('charitable_meta_box_after');
	}

	/**
	 * Display the fields to show inside a metabox.
	 *
	 * The fields parameter should contain an array of fields, 
	 * all of which are arrays with a 'priority' key and a 'view' 
	 * key.
	 *
	 * @param array $fields
	 * @return void
	 * @access public
	 * @since 0.0.1
	 */
	public function display_fields( array $fields ) {
		/**
		 * Sort the fields by priority.
		 */
		usort($fields, "charitable_priority_sort");

		/**
		 * Loop over the fields and display each one using the view provided.
		 */
		foreach( $fields as $field ) {
			charitable_admin_view( $field['view'] );
		}
	}

	/**
	 * Verifies that the user who is currently logged in has permission to save the data
	 * from the meta box to the database.
	 *
	 * Hat tip Tom McFarlin: http://tommcfarlin.com/wordpress-meta-boxes-each-component/
	 *
	 * @param integer $post_id The current post being saved.
	 * @return boolean True if the user can save the information
	 * @access public
	 * @since 0.0.1
	 */
	public function user_can_save( $post_id ) {
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ $this->nonce_name ] ) && wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) );
 
	    return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
	}
}

endif; // End class_exists check