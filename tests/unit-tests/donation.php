<?php

class Test_Charitable_Donation extends Charitable_UnitTestCase {

	private $post_1;

	private $donation_1;

	private $post_2;

	private $donation_2;

	private $campaign;

	function setUp() {
		parent::setUp();

		/**
		 * Create a campaign
		 */
		$campaign_id = $this->factory->campaign->create( array(
			'post_title' => 'Test Campaign', 
			'post_name' => 'test-campaign', 
			'post_type' => 'campaign', 
			'post_status' => 'publish' 
		) );

		$meta = array(
			'_campaign_goal_enabled' => 1,
			'_campaign_goal' => 40000.00,
			'_campaign_end_time_enabled' => 1,
			'_campaign_end_time' => date( 'Y-m-d H:i:s', strtotime( '+300 days') ),
			'_campaign_custom_donations_enabled' => 1,
			'_campaign_suggested_donations' => array( 5, 20, 50, 100, 250 )
		);

		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_id, $key, $value );
		}

		$this->campaign = new Charitable_Campaign( get_post( $campaign_id ) );

		/**
		 * Create a couple donations
		 */
		$user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );		

		$this->donation_id_1 = $this->factory->donation->create( array( 
			'campaign_id' 		=> $campaign_id, 
			'user_id'			=> $user_id, 
			'amount'			=> 100, 
			'gateway'			=> 'paypal', 
			'is_preset_amount' 	=> 1, 
			'notes'				=> 'Note', 
			'status'			=> 'Completed'
		) );
		$this->donation_1 = new Charitable_Donation( $this->donation_id_1 ); 

		$this->donation_id_2 = $this->factory->donation->create( array( 
			'campaign_id' 		=> $campaign_id, 
			'user_id'			=> $user_id, 
			'amount'			=> 75, 
			'gateway'			=> 'manual', 
			'is_preset_amount' 	=> 0
		) );			
		$this->donation_2 = new Charitable_Donation( $this->donation_id_2 );
	}

	function test_get_donation_id() {
		$this->assertEquals( $this->donation_id_1, $this->donation_1->get_donation_id() );
		$this->assertEquals( $this->donation_id_2, $this->donation_2->get_donation_id() );
	}	

	function test_is_preset_amount() {
		$this->assertEquals( 1, $this->donation_1->get_is_preset_amount() );
		$this->assertEquals( 0, $this->donation_2->get_is_preset_amount() );
	}

	function test_get_gateway() {
		$this->assertEquals( 'paypal', $this->donation_1->get_gateway() );
		$this->assertEquals( 'manual', $this->donation_2->get_gateway() );
	}

	function test_get_amount() {
		$this->assertEquals( 100, $this->donation_1->get_amount() );
		$this->assertEquals( 75, $this->donation_2->get_amount() );
	}

	function test_get_notes() {
		$this->assertEquals( 'Note', $this->donation_1->get_notes() );
		$this->assertEquals( '', $this->donation_2->get_notes() );
	}

	function test_get_status() {
		$this->assertEquals( 'Completed', $this->donation_1->get_status() );
		$this->assertEquals( 'Pending', $this->donation_2->get_status() );
	}
}