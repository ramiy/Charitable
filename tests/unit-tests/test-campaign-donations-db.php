<?php

class Test_Campaign_Donations_DB extends WP_UnitTestCase {
	
	public function setUp() {
		parent::setUp();

		$this->campaign_1 = Charitable_Campaign_Helper::create_campaign( array( 'post_title' => 'Campaign 1' ) );
		$this->campaign_2 = Charitable_Campaign_Helper::create_campaign( array( 'post_title' => 'Campaign 2' ) );

		$this->donation_1 = Charitable_Donation_Helper::create_donation( array( 'campaigns' => array(
			array(
				'campaign_id' 	=> $this->campaign_1, 
				'campaign_name' => get_the_title( $this->campaign_1 ), 
				'amount'		=> 10
			)
		) ) );

		$this->donation_2 = Charitable_Donation_Helper::create_donation( array( 'campaigns' => array(
			array(
				'campaign_id' 	=> $this->campaign_2, 
				'campaign_name' => get_the_title( $this->campaign_2 ), 
				'amount'		=> 10
			)
		) ) );

		$this->donation_3 = Charitable_Donation_Helper::create_donation( array( 'campaigns' => array(
			array(
				'campaign_id' 	=> $this->campaign_1, 
				'campaign_name' => get_the_title( $this->campaign_1 ), 
				'amount'		=> 30
			), 
			array(
				'campaign_id' 	=> $this->campaign_2, 
				'campaign_name' => get_the_title( $this->campaign_2 ), 
				'amount'		=> 40
			)
		) ) );
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_insert() {
		$args = array(
			'donation_id'	=> 0,
			'campaign_id'	=> $this->campaign_1,
			'amount'		=> 10
		);

		$campaign_donation_id = charitable_get_table('campaign_donations')->insert( $args );

		$this->assertGreaterThan( 0, $campaign_donation_id );
	}

	public function test_count_all() {
		$this->assertEquals( 4, charitable_get_table('campaign_donations')->count_all() );
	}

	/**
	 * @depends test_count_all
	 */
	public function test_get_donation_records() {

	}
}