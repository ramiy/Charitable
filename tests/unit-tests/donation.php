<?php

class Test_Charitable_Donation extends Charitable_UnitTestCase {

	private $donation_1;
	private $donation_2;

	private $campaign_1;
	private $campaign_2;

	private $donor_id;

	function setUp() {
		parent::setUp();

		/**
		 * Create a campaign
		 */
		$campaign_1_id = $this->factory->campaign->create( array(
			'post_title' 	=> 'Test Campaign', 
			'post_name' 	=> 'test-campaign', 
			'post_type' 	=> 'campaign', 
			'post_status' 	=> 'publish' 
		) );

		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_time_enabled' 			=> 1,
			'_campaign_end_time' 					=> date( 'Y-m-d H:i:s', strtotime( '+300 days') ),
			'_campaign_suggested_donations' 		=> array( 5, 20, 50, 100, 250 )
		);

		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_1_id, $key, $value );
		}

		$this->campaign_1 = new Charitable_Campaign( get_post( $campaign_1_id ) );

		/**
		 * Create a second campaign
		 */
		$campaign_2_id = $this->factory->campaign->create( array(
			'post_title' 	=> 'Test Campaign 2', 
			'post_name' 	=> 'test-campaign-2', 
			'post_type' 	=> 'campaign', 
			'post_status' 	=> 'publish' 
		) );

		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 10000.00,
			'_campaign_end_time_enabled' 			=> 0,
			'_campaign_suggested_donations' 		=> array( 5, 20, 50, 100, 250 )
		);

		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_2_id, $key, $value );
		}

		$this->campaign_2 = new Charitable_Campaign( get_post( $campaign_2_id ) );

		/**
		 * Create a couple donations
		 */
		$this->donor_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );		

		$this->donation_id_1 = $this->factory->donation->create( array( 
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_1_id,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 100
				)
			), 
			'status'			=> 'charitable-pending', 
			'gateway'			=> 'paypal',
			'note'				=> 'This is a note'			
		) );
		$this->donation_1 = new Charitable_Donation( $this->donation_id_1 ); 

		$this->donation_id_2 = $this->factory->donation->create( array( 
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_1_id,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 75
				), 
				array( 
					'campaign_id' 	=> $campaign_2_id,
					'campaign_name'	=> 'Test Campaign 2', 
					'amount'		=> 50
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'manual', 
		) );			
		$this->donation_2 = new Charitable_Donation( $this->donation_id_2 );
	}

	function test_get_campaign_donations_db() {
		$this->assertInstanceOf( 'Charitable_Campaign_Donations_DB', $this->donation_1->get_campaign_donations_db() );
	}

	function test_get_donation_id() {
		$this->assertEquals( $this->donation_id_1, $this->donation_1->get_donation_id() );
		$this->assertEquals( $this->donation_id_2, $this->donation_2->get_donation_id() );
	}	

	function test_get_gateway() {
		$this->assertEquals( 'paypal', $this->donation_1->get_gateway() );
		$this->assertEquals( 'manual', $this->donation_2->get_gateway() );
	}

	function test_get_total_donation_amount() {
		$this->assertEquals( 100, $this->donation_1->get_total_donation_amount() );
		$this->assertEquals( 125, $this->donation_2->get_total_donation_amount() );
	}

	function test_get_campaign_donations() {
		$this->assertEquals( 1, count( $this->donation_1->get_campaign_donations() ) );
		$this->assertEquals( 2, count( $this->donation_2->get_campaign_donations() ) );
	}

	function test_get_notes() {
		$this->assertEquals( 'This is a note', $this->donation_1->get_notes() );
		$this->assertEquals( '', $this->donation_2->get_notes() );
	}

	function test_get_status() {
		$this->assertEquals( 'charitable-pending', $this->donation_1->get_status() );
		$this->assertEquals( 'charitable-completed', $this->donation_2->get_status() );
	}

	function test_get_donor() {
		$this->assertInstanceOf( 'WP_User', $this->donation_1->get_donor() );
		$this->assertEquals( $this->donor_id, $this->donation_1->get_donor()->ID );
	}

	function test_get_valid_donation_statuses() {
		$this->assertArrayHasKey( 'charitable-pending', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-completed', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-failed', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-cancelled', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-refunded', Charitable_Donation::get_valid_donation_statuses() );
	}

	function test_donation_log() {
		$this->assertEquals( 1, count( $this->donation_2->get_donation_log() ) ); // 1 log created when donation inserted
		$this->donation_2->update_donation_log( 'New message' );

		$updated_log = $this->donation_2->get_donation_log();
		$this->assertEquals( 2, count( $updated_log ) ); 
		$this->assertEquals( 'New message', $updated_log[1]['message'] );
	}

	function test_insert() {
		$args = array(
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $this->campaign_1->get_campaign_id(),
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'manual'
		);

		$this->assertInternalType( 'int', Charitable_Donation::insert( $args ) );
	}

	function test_update_status() {
		$this->donation_1->update_status( 'charitable-completed' );
		$this->assertEquals( 'charitable-completed', $this->donation_1->get_status() );

		$log = $this->donation_1->get_donation_log();
		$last = array_pop( $log );
		$this->assertEquals( 'Donation status updated from Pending to Completed', $last['message'] );

		$this->donation_1->update_status( 'charitable-pending' ); // Stick it back to pending for other tests.

	}
}