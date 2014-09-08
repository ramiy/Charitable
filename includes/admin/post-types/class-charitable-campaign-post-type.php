<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Campaign_Post_Type' ) ) : 

/**
 * Charitable Campaign post type.
 *
 * @class 		Charitable_Campaign_Post_Type
 * @abstract
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Campaign Post Type
 * @version     0.1
 */
final class Charitable_Campaign_Post_Type {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * @var Charitable_Meta_Box_Helper $meta_box_helper
	 * @access private
	 */
	private $meta_box_helper;

	/**
	 * Create object instance. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-campaign' );

		add_action('add_meta_boxes', array( &$this, 'add_meta_boxes' ), 10);
		add_action('save_post', array( &$this, 'save_post' ), 10, 2);

		add_action('campaign_general_metabox', array( &$this, 'campaign_general_metabox' ));
		add_action('campaign_donations_metabox', array( &$this, 'campaign_donations_metabox' ));
	}

	/**
	 * Create an object instance. This will only work during the charitable_admin_start event.
	 * 
	 * @see charitable_admin_start hook
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	public static function charitable_admin_start(Charitable $charitable) {
		if ( ! $charitable->is_admin_start() ) {
			return;
		}

		new Charitable_Campaign_Post_Type($charitable);
	}

	/**
	 * Add meta boxes.
	 * 
	 * @see add_meta_boxes hook
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'campaign-general', 
			__( 'Campaign General Settings', 'charitable' ), 
			array( $this->meta_box_helper, 'display' ), 
			'campaign', 
			'normal', 
			'high', 
			array( 'view' => 'metaboxes/campaign-general-metabox' )
		);

		add_meta_box(
			'campaign-donation-form',
			__( 'Campaign Donation Settings', 'charitable' ), 
			array( $this->meta_box_helper, 'display' ), 
			'campaign',
			'normal',
			'high', 
			array( 'view' => 'metaboxes/campaign-donations-metabox' )
		);
	}

	/**
	 * Adds fields to the campaign general settings metabox. 
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function campaign_general_metabox() {

		/**
		 * Get the array of fields to be displayed within the 
		 * campaign settings metabox. 
		 */
		$fields = apply_filters( 'campaign_general_metabox_fields', 
			array(
				'goal' => array(
					'priority' => 4, 
					'view' => 'metaboxes/campaign-general/campaign-goal'
				), 
				'end_date' => array(
					'priority' => 8, 
					'view' => 'metaboxes/campaign-general/campaign-end-date'
				)
			) 
		);

		$this->meta_box_helper->display_fields( $fields );
	}

	/**
	 * Adds fields to the campaign donations metabox. 
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function campaign_donations_metabox() {
		/**
		 * Get the array of fields to be displayed within the 
		 * campaign donations metabox. 
		 */
		$fields = apply_filters( 'campaign_donations_metabox_fields', 
			array(
				'donations' => array(
					'priority' => 4, 
					'view' => 'metaboxes/campaign-donations/campaign-donation-options'
				)
			) 
		);

		$this->meta_box_helper->display_fields( $fields );
	}

	/**
	 * Save meta for the campaign. 
	 * 
	 * @param int $post_ID Post ID.
	 * @param WP_Post $post Post object.
	 * @return void
	 * @access public 
	 * @since 0.1
	 */
	public function save_post($post_id, WP_Post $post) {
		if ( $this->meta_box_helper->user_can_save( $post ) ) {
							
			$campaign_goal_enabled 				= isset( $_POST['_campaign_goal_enabled'] ) && $_POST['_campaign_goal_enabled'] == 'on';
			$campaign_goal 						= floatval( $_POST['_campaign_goal'] );
			$campaign_end_date_enabled 			= isset( $_POST['_campaign_end_date_enabled'] ) && $_POST['_campaign_end_date_enabled'] == 'on';
			$campaign_end_date 					= date( 'Y-m-d H:i:s', strtotime( $_POST['_campaign_end_date'] ) );
			$campaign_custom_donations_enabled 	= isset( $_POST['_campaign_custom_donations_enabled'] ) && $_POST['_campaign_custom_donations_enabled'] == 'on';
			$campaign_suggested_donations 		= $_POST['_campaign_suggested_donations'];
			$campaign_donation_form_fields 		= (array) $_POST['_campaign_donation_form_fields'];

			update_post_meta( $post_id, '_campaign_goal_enabled', $campaign_goal_enabled );
			update_post_meta( $post_id, '_campaign_goal', $campaign_goal );
			update_post_meta( $post_id, '_campaign_end_date_enabled', $campaign_end_date_enabled );
			update_post_meta( $post_id, '_campaign_end_date', $campaign_end_date );
			update_post_meta( $post_id, '_campaign_custom_donations_enabled', $campaign_custom_donations_enabled );
			update_post_meta( $post_id, '_campaign_suggested_donations', $campaign_suggested_donations );
		}
	}	
}

endif; // End class_exists check