<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Admin_Meta_Boxes_Campaign' ) ) : 

/**
 * Charitable Admin Meta Boxes Campaign.
 *
 * @class 		Charitable_Admin_Meta_Boxes_Campaign
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Campaign
 * @version     0.0.1
 */
class Charitable_Admin_Meta_Boxes_Campaign extends Charitable_Admin_Meta_Boxes {
	
	/**
	 * @var string Nonce action.
	 * @access protected
	 */
	protected $nonce_action = 'charitable-campaign';

	/**
	 * Create object instance. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.0.1
	 */
	private function __construct(Charitable $charitable) {
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
	 * @since 0.0.1
	 */
	public static function charitable_admin_start(Charitable $charitable) {
		if ( ! $charitable->is_admin_start() ) {
			return;
		}

		new Charitable_Admin_Meta_Boxes_Campaign($charitable);
	}

}

endif; // End class_exists check