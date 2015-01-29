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

		$fish = $this->factory->user->create( array( 
			'user_email'		=> 'fish@gotham.com',
			'first_name'		=> 'Fish', 
			'last_name'			=> 'Mooney' 
		) );

		Charitable_Donor::create( array(
			'user_email'		=> 'fish@gotham.com',
			'donor_address' 	=> '102 Bad Lane',
			'donor_address_2' 	=> '',
			'donor_city' 		=> 'Gotham',
			'donor_state' 		=> 'Gotham State',
			'donor_postcode' 	=> '29292',
			'donor_country' 	=> 'US'
		) );

		$carmine = $this->factory->user->create( array( 
			'user_email'		=> 'carmine@gotham.com',
			'first_name'		=> 'Carmine', 
			'last_name'			=> 'Falcone' 
		) );

		$james = Charitable_Donor::create( array(
			'user_email'		=> 'james@gotham.com',
			'first_name'		=> 'James',
			'last_name'			=> 'Gordon', 
			'donor_address' 	=> '22 Batman Avenue',
			'donor_address_2' 	=> '',
			'donor_city' 		=> 'Gotham',
			'donor_state' 		=> 'Gotham State',
			'donor_postcode' 	=> '29292',
			'donor_country' 	=> 'US'
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

	function test_get_address() {
		// "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}"
		$this->assertEquals( "James Gordon\n22 Batman Avenue\nGotham, Gotham State 29292\nUnited States (US)", $this->james_gordon->get_address() );
	}

	function test_get_donations() {

	}
}