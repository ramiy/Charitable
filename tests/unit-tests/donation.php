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
			'_campaign_suggested_donations' => array(
				5, 20, 50, 100, 250 
			),
			'_campaign_donation_form_fields' => array(
				'donor_first_name', 
				'donor_last_name', 
				'donor_email', 
				'donor_phone'
			)
		);

		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_id, $key, $value );
		}

		$this->campaign = new Charitable_Campaign( get_post( $campaign_id ) );

		/**
		 * Create a couple donations
		 */
		$user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );		

		$this->post_1 = $this->factory->donation->create_and_get( array( 'post_author' => $user_id ) );

		$meta = array(
			'_donation_amount' => 100,
			'_donation_gateway' => 'paypal',
			'_campaign_id' => $campaign_id, 
			'_donation_is_custom' => false
		);

		foreach ( $meta as $key => $value ) {
			update_post_meta( $this->post_1->ID, $key, $value );
		}

		$this->donation_1 = new Charitable_Donation( $this->post_1 ); 

		//second donation

		$this->post_2 = $this->factory->donation->create_and_get( array( 'post_author' => $user_id ) );

		$meta = array(
			'_donation_amount' => 75,
			'_donation_gateway' => 'stripe',
			'_campaign_id' => $campaign_id, 
			'_donation_is_custom' => true
		);

		foreach ( $meta as $key => $value ) {
			update_post_meta( $this->post_2->ID, $key, $value );
		}

		$this->donation_2 = new Charitable_Donation( $this->post_2 );
	}

	function test_get_donation_id() {
		$this->assertEquals( $this->post_1->ID, $this->donation_1->get_donation_id() );
		$this->assertEquals( $this->post_2->ID, $this->donation_2->get_donation_id() );
	}	

	function test_is_custom() {
		$this->assertEquals( false, $this->donation_1->get_is_custom() );
		$this->assertEquals( true, $this->donation_2->get_is_custom() );
	}

	function test_get_gateway() {
		$this->assertEquals( 'paypal', $this->donation_1->get_gateway() );
		$this->assertEquals( 'stripe', $this->donation_2->get_gateway() );
	}

	function test_get_amount() {
		$this->assertEquals( 100, $this->donation_1->get_amount() );
		$this->assertEquals( 75, $this->donation_2->get_amount() );
	}
}