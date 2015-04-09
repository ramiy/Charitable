<?php

class Test_Charitable_Donation extends WP_UnitTestCase {

	private $donation_1;
	private $donation_2;

	private $campaign_1;
	private $campaign_2;

	private $donor_id;

	public function setUp() {
		parent::setUp();

		/* Campaign 1: $40,000 goal, 300 days till end */
		$campaign_1_id 	= Charitable_Campaign_Helper::create_campaign( array( 
			'post_title'					=> 'Test Campaign 1',
			'_campaign_goal' 				=> 40000.00,
			'_campaign_end_date' 			=> date( 'Y-m-d H:i:s', strtotime( '+300 days' ) )
		) );

		$this->campaign_1 = new Charitable_Campaign( get_post( $campaign_1_id ) );

		/* Campaign 2: $40,000 goal, 300 days till end */
		$campaign_2_id 	= Charitable_Campaign_Helper::create_campaign( array( 
			'post_title'					=> 'Test Campaign 2',
			'_campaign_goal' 				=> 10000.00
		) );

		$this->campaign_2 = new Charitable_Campaign( get_post( $campaign_2_id ) );

		/* Create a couple donations */
		$this->donor_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );		

		$this->donation_id_1 = Charitable_Donation_Helper::create_donation( array(
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_1_id,
					'campaign_name'	=> 'Test Campaign 1', 
					'amount'		=> 100
				)
			), 
			'status'			=> 'charitable-pending', 
			'gateway'			=> 'paypal',
			'note'				=> 'This is a note'
		) );

		$this->donation_1 = new Charitable_Donation( $this->donation_id_1 ); 

		$this->donation_id_2 = Charitable_Donation_Helper::create_donation( array(
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_1_id,
					'campaign_name'	=> 'Test Campaign 1', 
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

	/** 
	 * Test insert method first. If this fails, we can skip most of the other tests.
	 */
	public function test_insert() {
		$args = array(
			'user_id'			=> $this->donor_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'manual'
		);

		$this->assertGreaterThan( 0, Charitable_Donation::insert( $args ) );
	}

	/**
	 * @depends test_insert
	 */
	public function test_insert_campaign_donations() {
		$campaigns = array(
			array( 
				'campaign_id' 	=> $this->campaign_1->ID,
				'campaign_name'	=> 'Test Campaign', 
				'amount'		=> 10
			)
		);

		$inserted = Charitable_Donation::insert_campaign_donations( 1, $campaigns );

		$this->assertEquals( 1, Charitable_Donation::insert_campaign_donations( 1, $campaigns ) );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_donation_id() {
		$this->assertEquals( $this->donation_id_1, $this->donation_1->get_donation_id() );
		$this->assertEquals( $this->donation_id_2, $this->donation_2->get_donation_id() );
	}	

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_gateway() {
		$this->assertEquals( 'paypal', $this->donation_1->get_gateway() );
		$this->assertEquals( 'manual', $this->donation_2->get_gateway() );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_total_donation_amount() {
		$this->assertEquals( 100, $this->donation_1->get_total_donation_amount() );
		$this->assertEquals( 125, $this->donation_2->get_total_donation_amount() );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_campaign_donations() {
		$this->assertEquals( 1, count( $this->donation_1->get_campaign_donations() ) );
		$this->assertEquals( 2, count( $this->donation_2->get_campaign_donations() ) );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_notes() {
		$this->assertEquals( 'This is a note', $this->donation_1->get_notes() );
		$this->assertEquals( '', $this->donation_2->get_notes() );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_status() {
		$this->assertEquals( 'charitable-pending', $this->donation_1->get_status() );
		$this->assertEquals( 'charitable-completed', $this->donation_2->get_status() );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_get_donor() {
		$this->assertInstanceOf( 'WP_User', $this->donation_1->get_donor() );
		$this->assertEquals( $this->donor_id, $this->donation_1->get_donor()->ID );
	}

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_donation_log() {
		$this->assertEquals( 1, count( $this->donation_2->get_donation_log() ) ); // 1 log created when donation inserted
		$this->donation_2->update_donation_log( 'New message' );

		$updated_log = $this->donation_2->get_donation_log();
		$this->assertEquals( 2, count( $updated_log ) ); 
		$this->assertEquals( 'New message', $updated_log[1]['message'] );
	}	

	/**
	 * @depends test_insert_campaign_donations
	 */
	public function test_update_status() {
		$this->donation_1->update_status( 'charitable-completed' );
		$this->assertEquals( 'charitable-completed', $this->donation_1->get_status() );

		$log = $this->donation_1->get_donation_log();
		$last = array_pop( $log );
		$this->assertEquals( 'Donation status updated from Pending to Completed', $last['message'] );

		$this->donation_1->update_status( 'charitable-pending' ); // Stick it back to pending for other tests.

	}

	public function test_get_valid_donation_statuses() {
		$this->assertArrayHasKey( 'charitable-pending', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-completed', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-failed', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-cancelled', Charitable_Donation::get_valid_donation_statuses() );
		$this->assertArrayHasKey( 'charitable-refunded', Charitable_Donation::get_valid_donation_statuses() );
	}

	public function test_get_campaign_donations_db() {
		$this->assertInstanceOf( 'Charitable_Campaign_Donations_DB', $this->donation_1->get_campaign_donations_db() );
	}

}