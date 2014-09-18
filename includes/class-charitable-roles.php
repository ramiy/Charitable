<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Roles' ) ) : 

/**
 * Roles and Capabilities for Charitable
 *
 * @class 		Charitable_Roles
 * @version		0.1
 * @package		Charitable/Classes/Core
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Roles {

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
	public function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

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

		new Charitable_Roles( $charitable );
	}

	/** 
	 * Sets up roles for Charitable. This is called by the install script. 
	 *
	 * @return void
	 * @since 0.1
	 */
	public function add_roles() {
		$capabilities = array(
			 'read' => true,
			 'delete_posts' => true,	
			 'edit_posts' => true,	
			 'delete_published_posts' => true,
			 'publish_posts' => true,
			 'upload_files' => true,
			 'edit_published_posts' => true,
			
			 'read_private_pages' => true,
			 'edit_private_pages' => true,
			 'delete_private_pages' => true,
			 'read_private_posts' => true,
			 'edit_private_posts' => true,
			 'delete_private_posts' => true,
			 'delete_others_posts' => true,
			 'delete_published_pages' => true,
			 'delete_others_pages' => true,
			 'delete_pages' => true,
			 'publish_pages' => true,
			 'edit_published_pages' => true,
			 'edit_others_pages' => true,
			 'edit_pages' => true,
			 'edit_others_posts' => true,
			 'manage_links' => true,
			 'manage_categories' => true,
			 'moderate_comments' => true,

			 'import' => true,	
			 'export' => true,
			 'unfiltered_html' => true,

			 'view_campaign_sensitive_data' => true,
			 'export_campaign_reports' => true,
			 'manage_campaign_settings' => true
			);


		add_role( "campaign_manager", __( "Campaign Manager" ), $capabilities );
	}

	/**
	 * Removes roles. This is called upon deactivation.
	 *
	 * @return void
	 * @since 0.1
	 */
	public function remove_roles() {
		remove_role("campaign_manager");
	}
}

endif; // End class_exists check.