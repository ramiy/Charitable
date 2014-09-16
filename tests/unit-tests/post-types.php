<?php

class Test_Charitable_Post_Types extends Charitable_UnitTestCase {

	function setUp() {
		parent::setUp();
	}

	function test_campaign_post_types() {
		global $wp_post_types;
		$this->assertArrayHasKey( 'campaign', $wp_post_types );
	}

	function test_campaign_post_type_labels() {
		global $wp_post_types;
		$this->assertEquals( 'Campaign', $wp_post_types['campaign']->labels->name );
		$this->assertEquals( 'Campaign', $wp_post_types['campaign']->labels->singular_name );
		$this->assertEquals( 'Campaign', $wp_post_types['campaign']->labels->menu_name );
		$this->assertEquals( 'Add Campaign', $wp_post_types['campaign']->labels->add_new );
		$this->assertEquals( 'Add New Campaign', $wp_post_types['campaign']->labels->add_new_item );
		$this->assertEquals( 'Edit', $wp_post_types['campaign']->labels->edit );
		$this->assertEquals( 'Edit Campaign', $wp_post_types['campaign']->labels->edit_item );
		$this->assertEquals( 'New Campaign', $wp_post_types['campaign']->labels->new_item );
		$this->assertEquals( 'View Campaign', $wp_post_types['campaign']->labels->view );
		$this->assertEquals( 'View Campaign', $wp_post_types['campaign']->labels->view_item );
		$this->assertEquals( 'Search Campaigns', $wp_post_types['campaign']->labels->search_items );
		$this->assertEquals( 'No Campaigns found', $wp_post_types['campaign']->labels->not_found );
		$this->assertEquals( 'No Campaigns found in trash', $wp_post_types['campaign']->labels->not_found_in_trash );
		$this->assertEquals( 'Parent Campaign', $wp_post_types['campaign']->labels->parent );
	}

	function test_donation_post_types() {
		global $wp_post_types;
		$this->assertArrayHasKey( 'donation', $wp_post_types );
	}

	function test_donation_post_type_labels() {
		global $wp_post_types;
		$this->assertEquals( 'Donation', $wp_post_types['donation']->labels->name );
		$this->assertEquals( 'Donation', $wp_post_types['donation']->labels->singular_name );
		$this->assertEquals( 'Donation', $wp_post_types['donation']->labels->menu_name );
		$this->assertEquals( 'Add Donation', $wp_post_types['donation']->labels->add_new );
		$this->assertEquals( 'Add New Donation', $wp_post_types['donation']->labels->add_new_item );
		$this->assertEquals( 'Edit', $wp_post_types['donation']->labels->edit );
		$this->assertEquals( 'Edit Donation', $wp_post_types['donation']->labels->edit_item );
		$this->assertEquals( 'New Donation', $wp_post_types['donation']->labels->new_item );
		$this->assertEquals( 'View Donation', $wp_post_types['donation']->labels->view );
		$this->assertEquals( 'View Donation', $wp_post_types['donation']->labels->view_item );
		$this->assertEquals( 'Search Donations', $wp_post_types['donation']->labels->search_items );
		$this->assertEquals( 'No Donations found', $wp_post_types['donation']->labels->not_found );
		$this->assertEquals( 'No Donations found in trash', $wp_post_types['donation']->labels->not_found_in_trash );
		$this->assertEquals( 'Parent Donation', $wp_post_types['donation']->labels->parent );
	}
}