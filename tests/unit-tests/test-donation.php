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
		$this->user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );
		$user = new Charitable_User( $this->user_id );
		$this->donor_id = $user->add_donor();
	}

	/** 
	 * Test insert method first. If this fails, we can skip most of the other tests.
	 */
	public function test_add_donation() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			)
		) );
		
		$this->assertGreaterThan( 0, $donation_id );
	}

	/**
	 * @depends test_add_donation
	 */
	public function test_get_ID() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			)
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertEquals( $donation_id, $donation->ID );
	}	

	/**
	 * @depends test_add_donation
	 */
	public function test_get_gateway() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'gateway' => 'stripe'
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertEquals( 'stripe', $donation->get_gateway() );
	}

	/**
	 * @depends test_add_donation
	 */
	public function test_get_total_donation_amount() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			)
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertEquals( 10, $donation->get_total_donation_amount() );
	}

	/**
	 * @depends test_add_donation
	 * @depends test_get_total_donation_amount
	 */
	public function test_get_total_donation_amount_multi_donation() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 50
				), 
				array( 
					'campaign_id' 	=> $this->campaign_2->ID,
					'campaign_name'	=> 'Test Campaign 2', 
					'amount'		=> 75
				)
			)
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertEquals( 125, $donation->get_total_donation_amount() );
	}

	/**
	 * @depends test_add_donation
	 * @depends test_get_total_donation_amount
	 */
	public function test_get_campaign_donations() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			)
		) );

		$donation = charitable_get_donation( $donation_id );
		
		$this->assertCount( 1, $donation->get_campaign_donations() );
	}

	/**
	 * @depends test_add_donation
	 * @depends test_get_total_donation_amount
	 * @depends test_get_campaign_donations
	 */
	public function test_get_campaign_donations_multi() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 50
				), 
				array( 
					'campaign_id' 	=> $this->campaign_2->ID,
					'campaign_name'	=> 'Test Campaign 2', 
					'amount'		=> 75
				)
			)
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertCount( 2, $donation->get_campaign_donations() );
	}	

	/**
	 * @depends test_add_donation
	 */
	public function test_get_notes() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'note' => 'This is a note'
		) );
		
		$donation = charitable_get_donation( $donation_id );
		
		$this->assertEquals( 'This is a note', $donation->get_notes() );
	}

	/**
	 * @depends test_add_donation
	 */
	public function test_get_status() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-completed'
		) );

		$donation = charitable_get_donation( $donation_id );

		$this->assertEquals( 'charitable-completed', $donation->get_status() );
	}

	/**
	 * @depends test_add_donation
	 */
	public function test_get_donor() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-completed'
		) );
		
		$donation = charitable_get_donation( $donation_id );

		$this->assertInstanceOf( 'Charitable_Donor', $donation->get_donor() );
	}

	/**
	 * @depends test_add_donation
	 */
	public function test_get_donation_log() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-completed'
		) );

		$donation = charitable_get_donation( $donation_id );
		
		$this->assertCount( 1, $donation->get_donation_log() );
	}	

	/**
	 * @depends test_add_donation
	 * @depends test_get_donation_log
	 */
	public function test_update_donation_log() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-completed'
		) );

		$donation = charitable_get_donation( $donation_id );
		
		$donation->update_donation_log( 'New message' );

		$this->assertCount( 2, $donation->get_donation_log() );
	}		

	/**
	 * @depends test_add_donation
	 */
	public function test_update_status() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-pending'
		) );

		$donation = charitable_get_donation( $donation_id );

		$donation->update_status( 'charitable-completed' );

		$this->assertEquals( 'charitable-completed', $donation->get_status() );
	}

	/**
	 * @depends test_update_status
	 */
	public function test_update_status_log() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-pending'
		) );

		$donation = charitable_get_donation( $donation_id );

		$donation->update_status( 'charitable-completed' );

		$log = $donation->get_donation_log();
		$last = array_pop( $log );

		$this->assertEquals( 'Donation status updated from Pending to Completed', $last['message'] );
	}

	public function test_get_campaigns() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign 1', 
					'amount'		=> 10
				),
				array( 
					'campaign_id' 	=> $this->campaign_2->ID,
					'campaign_name'	=> 'Test Campaign 2', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-pending'
		) );

		$donation = charitable_get_donation( $donation_id );

		$this->assertCount( 2, $donation->get_campaigns() );
	}

	public function test_get_campaigns_donated_to() {
		$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array( 
					'campaign_id' 	=> $this->campaign_1->ID,
					'campaign_name'	=> 'Test Campaign 1', 
					'amount'		=> 10
				),
				array( 
					'campaign_id' 	=> $this->campaign_2->ID,
					'campaign_name'	=> 'Test Campaign 2', 
					'amount'		=> 10
				)
			), 
			'status' => 'charitable-pending'
		) );

		$donation = charitable_get_donation( $donation_id );

		$this->assertEquals( 'Test Campaign 1, Test Campaign 2', $donation->get_campaigns_donated_to() );
	}

	public function test_get_valid_donation_statuses() {
		$this->assertCount( 5, charitable_get_valid_donation_statuses() );	
	}

}