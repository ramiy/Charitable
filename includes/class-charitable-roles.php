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
class Charitable_Roles {

	/** 
	 * Sets up roles for Charitable. This is called by the install script. 
	 *
	 * @return 	void
	 * @since 	0.1
	 */
	public function add_roles() {
		add_role( 'campaign_manager', __( 'Campaign Manager', 'charitable' ), array(
			'read' 						=> true,
			'delete_posts' 				=> true,	
			'edit_posts' 				=> true,	
			'delete_published_posts' 	=> true,
			'publish_posts' 			=> true,
			'upload_files' 				=> true,
			'edit_published_posts' 		=> true,
			'read_private_pages' 		=> true,
			'edit_private_pages' 		=> true,
			'delete_private_pages' 		=> true,
			'read_private_posts' 		=> true,
			'edit_private_posts' 		=> true,
			'delete_private_posts' 		=> true,
			'delete_others_posts' 		=> true,
			'delete_published_pages' 	=> true,
			'delete_others_pages' 		=> true,
			'delete_pages' 				=> true,
			'publish_pages' 			=> true,
			'edit_published_pages' 		=> true,
			'edit_others_pages' 		=> true,
			'edit_pages' 				=> true,
			'edit_others_posts' 		=> true,
			'manage_links' 				=> true,
			'manage_categories' 		=> true,
			'moderate_comments' 		=> true,
			'import' 					=> true,	
			'export' 					=> true,
			'unfiltered_html'		 	=> true			
		) );
	}

	/** 
	 * Sets up capabilities for Charitable. This is called by the install script. 
	 *
	 * @global 	WP_Roles
	 * @return 	void
	 * @since 	0.1
	 */
	public function add_caps() {
		global $wp_roles;

		if ( class_exists('WP_Roles') ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {

			$wp_roles->add_cap( 'campaign_manager', 'view_campaign_sensitive_data' );
			$wp_roles->add_cap( 'campaign_manager', 'export_campaign_reports' );
			$wp_roles->add_cap( 'campaign_manager', 'manage_campaign_settings' );

			$wp_roles->add_cap( 'administrator', 'view_campaign_sensitive_data' );
			$wp_roles->add_cap( 'administrator', 'export_campaign_reports' );
			$wp_roles->add_cap( 'administrator', 'manage_campaign_settings' );

			// Add the main post type capabilities
			foreach ( $this->get_core_caps() as $cap ) {
				$wp_roles->add_cap( 'campaign_manager', $cap );
				$wp_roles->add_cap( 'administrator', $cap );
			}
		}
	}

	/**
	 * Removes roles. This is called upon deactivation.
	 *
	 * @global 	WP_Roles
	 * @return 	void
	 * @access 	public
	 * @since 	0.1
	 */
	public function remove_caps() {
		global $wp_roles;

		if ( class_exists('WP_Roles') ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {

			$wp_roles->remove_cap( 'campaign_manager', 'view_campaign_sensitive_data' );
			$wp_roles->remove_cap( 'campaign_manager', 'export_campaign_reports' );
			$wp_roles->remove_cap( 'campaign_manager', 'manage_campaign_settings' );

			$wp_roles->remove_cap( 'administrator', 'view_campaign_sensitive_data' );
			$wp_roles->remove_cap( 'administrator', 'export_campaign_reports' );
			$wp_roles->remove_cap( 'administrator', 'manage_campaign_settings' );

			// Remove the main post type capabilities
			foreach ( $this->get_core_caps() as $cap ) {
				$wp_roles->remove_cap( 'campaign_manager', $cap );
				$wp_roles->remove_cap( 'administrator', $cap );
			}
		}
	}

	/**
	 * Returns the caps for the post types that Charitable adds. 
	 *
	 * @return 	array
	 * @access 	private
	 * @since 	0.1
	 */
	private function get_core_caps() {
		return array(
			// Post type
			'edit_campaign',
			'read_campaign',
			'delete_campaign',
			'edit_campaigns',
			'edit_others_campaigns',
			'publish_campaigns',
			'read_private_campaigns',
			'delete_campaigns',
			'delete_private_campaigns',
			'delete_published_campaigns',
			'delete_others_campaigns',
			'edit_private_campaigns',
			'edit_published_campaigns',

			// Terms
			'manage_campaign_terms',
			'edit_campaign_terms',
			'delete_campaign_terms',
			'assign_campaign_terms'
		);
	}
}

endif; // End class_exists check.