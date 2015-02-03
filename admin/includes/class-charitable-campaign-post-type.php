<?php
/**
 * The class that defines how campaigns are managed on the admin side.
 *
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Campaign Post Type
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Campaign_Post_Type' ) ) : 

/**
 * Charitable_Campaign_Post_Type class.
 *
 * @final
 * @since 	    1.0.0
 */
final class Charitable_Campaign_Post_Type {

	/**
	 * @var 	Charitable 		$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * @var 	Charitable_Meta_Box_Helper $meta_box_helper
	 * @access 	private
	 */
	private $meta_box_helper;

	/**
	 * Create object instance. 
	 *
	 * @param 	Charitable 		$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-campaign' );

		add_action( 'edit_form_after_title', 		array( $this, 'campaign_form_top' ) );
		add_action( 'add_meta_boxes', 				array( $this, 'add_meta_boxes' ), 10);
		add_action( 'save_post', 					array( $this, 'save_post' ), 10, 2);
		add_action( 'campaign_general_metabox', 	array( $this, 'campaign_general_metabox' ));
		add_action( 'campaign_donations_metabox', 	array( $this, 'campaign_donations_metabox' ));
		add_filter( 'enter_title_here', 			array( $this, 'campaign_enter_title' ), 10, 2 );
	}

	/**
	 * Create an object instance. This will only work during the charitable_start event.
	 * 
	 * @see 	charitable_start hook
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

		new Charitable_Campaign_Post_Type($charitable);
	}

	/**
	 * Add meta boxes.
	 * 
	 * @see 	add_meta_boxes hook
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function add_meta_boxes() {
		$meta_boxes = array(
			array(
				'id'		=> 'campaign-title', 
				'title'		=> __( 'Campaign Title', 'charitable' ), 
				'context'	=> 'campaign-top', 
				'priority'	=> 'high', 
				'view'		=> 'metaboxes/campaign-title'
			),
			array( 
				'id' 		=> 'campaign-goal', 
				'title' 	=> __( 'Campaign Goal ($)', 'charitable' ), 
				'context'	=> 'campaign-top', 
				'priority'	=> 'high', 
				'view' 		=> 'metaboxes/campaign-goal'
			), 
			array( 
				'id' 		=> 'campaign-description', 
				'title' 	=> __( 'Campaign Description', 'charitable' ), 
				'context'	=> 'campaign-top', 
				'priority'	=> 'high', 
				'view' 		=> 'metaboxes/campaign-description'
			),
			array(
				'id' 		=> 'campaign-donation-form',
				'title' 	=> __( 'Campaign Donation Settings', 'charitable' ), 
				'context'	=> 'normal', 
				'priority'	=> 'high', 
				'view' 		=> 'metaboxes/campaign-donations-metabox'
			)
		);

		apply_filters( 'charitable_campaign_meta_boxes', $meta_boxes );

		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box( 
				$meta_box['id'], 
				$meta_box['title'], 
				array( $this->meta_box_helper, 'metabox_display' ), 
				'campaign', 
				$meta_box['context'], 
				$meta_box['priority'], 
				array( 
					'view' 	=> $meta_box['view'], 
					'title'	=> $meta_box['title']
				) 
			);
		}
	}

	/**
	 * Display fields at the very top of the page. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function campaign_form_top( $post ) {
		// global $wp_meta_boxes;
		// echo '<pre>'; print_r( $wp_meta_boxes['campaign']['top'] );
		// die;
		if ( 'campaign' == $post->post_type ) {
			do_meta_boxes( 'campaign', 'campaign-top', $post );
		}		
	}

	/**
	 * Adds fields to the campaign general settings metabox. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
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
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
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
	 * @param 	int 		$post_ID 	Post ID.
	 * @param 	WP_Post 	$post 		Post object.
	 * @return 	void
	 * @access 	public 
	 * @since 	1.0.0
	 */
	public function save_post($post_id, WP_Post $post) {
		if ( $this->meta_box_helper->user_can_save( $post ) ) {
					
			$campaign_goal_enabled 				= isset( $_POST['_campaign_goal_enabled'] ) && $_POST['_campaign_goal_enabled'] == 'on';
			$campaign_goal 						= floatval( $_POST['_campaign_goal'] );
			$campaign_end_date_enabled 			= isset( $_POST['_campaign_end_date_enabled'] ) && $_POST['_campaign_end_date_enabled'] == 'on';
			$campaign_end_date 					= date( 'Y-m-d H:i:s', strtotime( $_POST['_campaign_end_date'] ) );
			$campaign_suggested_donations 		= $_POST['_campaign_suggested_donations'];
			$campaign_donation_form_fields 		= (array) $_POST['_campaign_donation_form_fields'];

			update_post_meta( $post_id, '_campaign_goal_enabled', $campaign_goal_enabled );
			update_post_meta( $post_id, '_campaign_goal', $campaign_goal );
			update_post_meta( $post_id, '_campaign_end_date_enabled', $campaign_end_date_enabled );
			update_post_meta( $post_id, '_campaign_end_date', $campaign_end_date );
			update_post_meta( $post_id, '_campaign_suggested_donations', $campaign_suggested_donations );
		}
	}	

	/**
	 * Sets the placeholder text of the campaign title field. 
	 *
	 * @param 	string 		$placeholder
	 * @param 	WP_Post 	$post
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function campaign_enter_title( $placeholder, WP_Post $post ) {		
		if ( $post->post_type == 'campaign' ) {
			$placeholder = __( 'Enter campaign title', 'charitable' );
		}

		return $placeholder;
	}
}

endif; // End class_exists check