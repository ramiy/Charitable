<?php

class Test_Charitable_Post_Types extends Charitable_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );

		$this->qvs = $GLOBALS['wp']->public_query_vars;

		/**
		 * Temporary workaround for issue noted below.
		 * @see https://core.trac.wordpress.org/ticket/37207
		 */
		Charitable_Post_Types::get_instance()->add_endpoints();
	}

	public function tearDown() {
		$GLOBALS['wp']->public_query_vars = $this->qvs;
		parent::tearDown();
	}

	public function test_is_campaign_post_type_registered() {
		global $wp_post_types;
		$this->assertArrayHasKey( 'campaign', $wp_post_types );
	}

	public function test_campaign_post_type_labels() {
		global $wp_post_types;
		$this->assertEquals( 'Campaigns', $wp_post_types['campaign']->labels->name );
		$this->assertEquals( 'Campaign', $wp_post_types['campaign']->labels->singular_name );
		$this->assertEquals( 'Campaigns', $wp_post_types['campaign']->labels->menu_name );
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

	public function test_is_donation_post_type_registered() {
		global $wp_post_types;
		$this->assertArrayHasKey( 'donation', $wp_post_types );
	}

	public function test_donation_post_type_labels() {
		global $wp_post_types;
		$this->assertEquals( 'Donations', $wp_post_types['donation']->labels->name );
		$this->assertEquals( 'Donation', $wp_post_types['donation']->labels->singular_name );
		$this->assertEquals( 'Donations', $wp_post_types['donation']->labels->menu_name );
		$this->assertEquals( 'Add Donation', $wp_post_types['donation']->labels->add_new );
		$this->assertEquals( 'Add New Donation', $wp_post_types['donation']->labels->add_new_item );
		$this->assertEquals( 'Edit', $wp_post_types['donation']->labels->edit );
		$this->assertEquals( 'Donation Details', $wp_post_types['donation']->labels->edit_item );
		$this->assertEquals( 'New Donation', $wp_post_types['donation']->labels->new_item );
		$this->assertEquals( 'View Donation', $wp_post_types['donation']->labels->view );
		$this->assertEquals( 'View Donation', $wp_post_types['donation']->labels->view_item );
		$this->assertEquals( 'Search Donations', $wp_post_types['donation']->labels->search_items );
		$this->assertEquals( 'No Donations found', $wp_post_types['donation']->labels->not_found );
		$this->assertEquals( 'No Donations found in trash', $wp_post_types['donation']->labels->not_found_in_trash );
		$this->assertEquals( 'Parent Donation', $wp_post_types['donation']->labels->parent );
	}

	public function test_custom_post_statuses() {
		global $wp_post_statuses;
		$this->assertArrayHasKey( 'charitable-pending', $wp_post_statuses );
		$this->assertArrayHasKey( 'charitable-completed', $wp_post_statuses );
		$this->assertArrayHasKey( 'charitable-failed', $wp_post_statuses );
		$this->assertArrayHasKey( 'charitable-cancelled', $wp_post_statuses );
		$this->assertArrayHasKey( 'charitable-refunded', $wp_post_statuses );
		$this->assertArrayHasKey( 'charitable-preapproved', $wp_post_statuses );
	}

	/**
	 * @covers Charitable_Post_Types::add_endpoints()
	 */
	public function test_is_donate_endpoint_added() {
		$this->assertContains( 'donate', $GLOBALS['wp']->public_query_vars );
	}

	/**
	 * @covers Charitable_Post_Types::add_endpoints()
	 */
	public function test_is_widget_endpoint_added() {
		$this->assertContains( 'widget', $GLOBALS['wp']->public_query_vars );
	}

	/**
	 * @covers Charitable_Post_Types::add_endpoints()
	 */
	public function test_is_donation_receipt_endpoint_added() {
		$this->assertContains( 'donation_receipt', $GLOBALS['wp']->public_query_vars );
	}

	/**
	 * @covers Charitable_Post_Types::add_endpoints()
	 */
	public function test_is_donation_processing_endpoint_added() {
		$this->assertContains( 'donation_processing', $GLOBALS['wp']->public_query_vars );
	}
}
