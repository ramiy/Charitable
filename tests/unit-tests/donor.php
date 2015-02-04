<?php

class Test_Charitable_Donor extends Charitable_UnitTestCase {

	/**
	 * We have three different users to test several different 
	 * ways a donor may be created. 
	 *
	 * 1. James Gordon: Totally new user when he makes his first donation.
	 * 2. Fish Mooney: An existing user who makes a donation. 
	 * 3. Carmine Falcone: An existing user who has never made a donation and is therefore not a donor.
	 */
	private $james_gordon;
	private $fish_mooney;
	private $carmine_falcone;

	function setUp() {
		parent::setUp();

		// Fish Mooney is created as a user.
		$fish = $this->factory->user->create( array( 
			'user_email'		=> 'fish@gotham.com',
			'first_name'		=> 'Fish', 
			'last_name'			=> 'Mooney' 
		) );

		// Later, Fish makes a donation.
		Charitable_Donor::create( array(
			'user_email'		=> 'fish@gotham.com',
			'address' 			=> '102 Bad Lane',
			'address_2' 		=> '',
			'city' 				=> 'Gotham',
			'state' 			=> 'Gotham State',
			'postcode' 			=> '29292',
			'country' 			=> 'US'
		) );

		// Carmine Falcone is created as a user.
		$carmine = $this->factory->user->create( array( 
			'user_email'		=> 'carmine@gotham.com',
			'first_name'		=> 'Carmine', 
			'last_name'			=> 'Falcone' 
		) );

		// James Gordon makes a donation. 
		$james = Charitable_Donor::create( array(
			'user_email'		=> 'james@gotham.com',
			'first_name'		=> 'James',
			'last_name'			=> 'Gordon', 
			'address' 			=> '22 Batman Avenue',
			'address_2' 		=> '',
			'city' 				=> 'Gotham',
			'state' 			=> 'Gotham State',
			'postcode' 			=> '29292',
			'country' 			=> 'US'
		) );

		$campaign_id = $this->factory->campaign->create( array(
			'post_title' 	=> 'Test Campaign 1', 
			'post_name' 	=> 'test-campaign-1', 
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
			update_post_meta( $campaign_id, $key, $value );
		}

		$this->factory->donation->create( array( 
			'user_id'			=> $james, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_id,
					'campaign_name'	=> 'Test Campaign 1', 
					'amount'		=> 100
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal',
			'note'				=> 'This is a note'			
		) );

		$this->james_gordon = new Charitable_Donor( $james );
		$this->fish_mooney = new Charitable_Donor( $fish );
		$this->carmine_falcone = new Charitable_Donor( $carmine );
	}

	function test_is_donor() {
		$this->assertEquals( 1, $this->james_gordon->is_donor() );
		$this->assertEquals( 1, $this->fish_mooney->is_donor() );
		$this->assertEquals( 0, $this->carmine_falcone->is_donor() );
	}

	function test_get_name() {
		$this->assertEquals( 'James Gordon', $this->james_gordon->get_name() );
		$this->assertEquals( 'Fish Mooney', $this->fish_mooney->get_name() );
		$this->assertEquals( 'Carmine Falcone', $this->carmine_falcone->get_name() );
	}

	function test_get_address_fields() {
		$this->assertContains( 'donor_address', $this->james_gordon->get_address_fields() );
		$this->assertContains( 'donor_address_2', $this->james_gordon->get_address_fields() );
		$this->assertContains( 'donor_city', $this->james_gordon->get_address_fields() );
		$this->assertContains( 'donor_state', $this->james_gordon->get_address_fields() );
		$this->assertContains( 'donor_postcode', $this->james_gordon->get_address_fields() );
		$this->assertContains( 'donor_country', $this->james_gordon->get_address_fields() );
	}

	function test_get() {
		$this->assertEquals( 'james@gotham.com', $this->james_gordon->get('user_email') );
		$this->assertEquals( 'James', $this->james_gordon->get('first_name') );
		$this->assertEquals( 'Gordon',  $this->james_gordon->get('last_name') );
		$this->assertEquals( '22 Batman Avenue', $this->james_gordon->get('donor_address') );
		$this->assertEquals( '', $this->james_gordon->get('donor_address_2') );
		$this->assertEquals( 'Gotham', $this->james_gordon->get('donor_city') );
		$this->assertEquals( 'Gotham State', $this->james_gordon->get('donor_state') );
		$this->assertEquals( '29292', $this->james_gordon->get('donor_postcode') );
		$this->assertEquals( 'US', $this->james_gordon->get('donor_country') );	
	}

	function test_get_address() {
		// "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}"
		$expected = "James Gordon<br/>22 Batman Avenue<br/>Gotham, GOTHAM STATE 29292<br/>United States (US)";
		$this->assertEquals( $expected, $this->james_gordon->get_address() );
	}	

	function test_get_donations() {
		$this->assertCount( 1, $this->james_gordon->get_donations() );
	}

	function test_get_total_donated() {
		$this->assertEquals( 100.00, $this->james_gordon->get_total_donated() );
		$this->assertInternalType( 'float', $this->james_gordon->get_total_donated() );
	}
}