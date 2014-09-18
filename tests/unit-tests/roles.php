<?php

class Test_Charitable_Roles extends Charitable_UnitTestCase {

	private $roles;

	function setUp() {
		parent::setUp();

		$this->roles = new Charitable_Roles(Charitable::get_instance());
		$this->roles->add_roles();
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

		$campaign_manager_caps = get_role('campaign_manager')->capabilities;

		$this->assertEquals( $campaign_manager_caps['read'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_posts'], 1 );	
		$this->assertEquals( $campaign_manager_caps['edit_posts'], 1 );	
		$this->assertEquals( $campaign_manager_caps['delete_published_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['publish_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['upload_files'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_published_posts'], 1 );
		
		$this->assertEquals( $campaign_manager_caps['read_private_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_private_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_private_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['read_private_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_private_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_private_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_others_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_published_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_others_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['delete_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['publish_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_published_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_others_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_pages'], 1 );
		$this->assertEquals( $campaign_manager_caps['edit_others_posts'], 1 );
		$this->assertEquals( $campaign_manager_caps['manage_links'], 1 );
		$this->assertEquals( $campaign_manager_caps['manage_categories'], 1 );
		$this->assertEquals( $campaign_manager_caps['moderate_comments'], 1 );

		$this->assertEquals( $campaign_manager_caps['import'], 1 );	
		$this->assertEquals( $campaign_manager_caps['export'], 1 );
		$this->assertEquals( $campaign_manager_caps['unfiltered_html'], 1 );

		$this->assertEquals( $campaign_manager_caps['view_campaign_sensitive_data'], 1 );
		$this->assertEquals( $campaign_manager_caps['export_campaign_reports'], 1 );
		$this->assertEquals( $campaign_manager_caps['manage_campaign_settings'], 1 );
	}

	function test_remove_roles() {
		$this->roles->remove_roles();

		global $wp_roles;

		$this->assertFalse( array_key_exists('campaign_manager', (array) $wp_roles->role_names) );
	
	}
}