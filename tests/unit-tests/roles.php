<?php

class Test_Charitable_Roles extends Charitable_UnitTestCase {

	private $roles;

	function setUp() {
		parent::setUp();

		$this->roles = new Charitable_Roles();
		$this->roles->add_roles();
		$this->roles->add_caps();
	}

	function test_add_roles() {
		global $wp_roles;
		$this->assertArrayHasKey( 'campaign_manager', (array) $wp_roles->role_names );
	}

	function test_manager_caps() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		$campaign_manager_caps = $campaign_manager_caps;

		$this->assertArrayHasKey( 'read', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_posts', $campaign_manager_caps );	
		$this->assertArrayHasKey( 'edit_posts', $campaign_manager_caps );	
		$this->assertArrayHasKey( 'delete_published_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'publish_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'upload_files', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_published_posts', $campaign_manager_caps );
		
		$this->assertArrayHasKey( 'read_private_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_private_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_private_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'read_private_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_private_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_private_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_others_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_published_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_others_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'delete_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'publish_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_published_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_others_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_pages', $campaign_manager_caps );
		$this->assertArrayHasKey( 'edit_others_posts', $campaign_manager_caps );
		$this->assertArrayHasKey( 'manage_links', $campaign_manager_caps );
		$this->assertArrayHasKey( 'manage_categories', $campaign_manager_caps );
		$this->assertArrayHasKey( 'moderate_comments', $campaign_manager_caps );

		$this->assertArrayHasKey( 'import', $campaign_manager_caps );	
		$this->assertArrayHasKey( 'export', $campaign_manager_caps );
		$this->assertArrayHasKey( 'unfiltered_html', $campaign_manager_caps );

		$this->assertArrayHasKey( 'view_campaign_sensitive_data', $campaign_manager_caps );
		$this->assertArrayHasKey( 'export_campaign_reports', $campaign_manager_caps );
		$this->assertArrayHasKey( 'manage_campaign_settings', $campaign_manager_caps );
	}

	function test_remove_roles() {
		$this->roles->remove_roles();

		global $wp_roles;

		$this->assertFalse( array_key_exists('campaign_manager', (array) $wp_roles->role_names) );
	
	}
}