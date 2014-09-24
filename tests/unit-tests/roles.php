<?php

class Test_Charitable_Roles extends WP_UnitTestCase {

	private $roles;

	function setUp() {
		parent::setUp();

		$this->charitable = get_charitable();
		
		$this->roles = new Charitable_Roles( $this->charitable );
		$this->roles->add_roles();
		$this->roles->add_caps();
	}

	function test_add_roles() {
		global $wp_roles;
		
		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		$this->assertArrayHasKey( 'campaign_manager', (array) $wp_roles->role_names );
	}

	function test_manager_caps() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		$this->assertArrayHasKey( 'read', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );	
		$this->assertArrayHasKey( 'edit_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );	
		$this->assertArrayHasKey( 'delete_published_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'publish_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'upload_files', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_published_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		
		$this->assertArrayHasKey( 'read_private_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_private_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_private_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'read_private_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_private_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_private_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_others_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_published_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_others_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'publish_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_published_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_others_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_pages', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_others_posts', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'manage_links', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'manage_categories', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'moderate_comments', (array) $wp_roles->roles['campaign_manager']['capabilities'] );

		$this->assertArrayHasKey( 'import', (array) $wp_roles->roles['campaign_manager']['capabilities'] );	
		$this->assertArrayHasKey( 'export', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'unfiltered_html', (array) $wp_roles->roles['campaign_manager']['capabilities'] );

		$this->assertArrayHasKey( 'view_campaign_sensitive_data', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'export_campaign_reports', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'manage_campaign_settings', (array) $wp_roles->roles['campaign_manager']['capabilities'] );

		$this->assertArrayHasKey( 'edit_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'read_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_others_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'publish_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'read_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_published_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_others_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_published_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'manage_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'edit_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
		$this->assertArrayHasKey( 'assign_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] );
	}

	function test_admin_caps() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		$this->assertArrayHasKey( 'view_campaign_sensitive_data', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'export_campaign_reports', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'manage_campaign_settings', (array) $wp_roles->roles['administrator']['capabilities'] );

		$this->assertArrayHasKey( 'edit_campaign', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'read_campaign', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaign', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'edit_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'edit_others_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'publish_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'read_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_published_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_others_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'edit_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'edit_published_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'manage_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'edit_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'delete_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] );
		$this->assertArrayHasKey( 'assign_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] );
	}

	function test_deactivation() {
		global $wp_roles;

		$this->roles->remove_caps();
	
		$this->assertFalse( array_key_exists( 'view_campaign_sensitive_data', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'export_campaign_reports', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'manage_campaign_settings', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'read_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaign', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_others_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'publish_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'read_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_published_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_others_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_private_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_published_campaigns', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'manage_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'assign_campaign_terms', (array) $wp_roles->roles['campaign_manager']['capabilities'] ) );

		$this->assertFalse( array_key_exists( 'view_campaign_sensitive_data', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'export_campaign_reports', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'manage_campaign_settings', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaign', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'read_campaign', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaign', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_others_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'publish_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'read_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_published_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_others_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_private_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_published_campaigns', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'manage_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'edit_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'delete_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] ) );
		$this->assertFalse( array_key_exists( 'assign_campaign_terms', (array) $wp_roles->roles['administrator']['capabilities'] ) );
	}
}