<?php

class Test_Charitable_User extends WP_UnitTestCase {

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

	public function setUp() {
		parent::setUp();

		add_filter( 'charitable_auto_login_after_registration', '__return_false' );

		/* James Gordon makes a donation and becomes a donor/user in the process. */
		$this->james_gordon = Charitable_User::create_profile( array(
			'user_email'		=> 'james@gotham.com',
			'first_name'		=> 'James',
			'last_name'			=> 'Gordon', 
			'user_pass' 		=> 'password', // Required for the user to be created at the moment.
			'address' 			=> '22 Batman Avenue',
			'address_2' 		=> '',
			'city' 				=> 'Gotham',
			'state' 			=> 'Gotham State',
			'postcode' 			=> '29292',
			'country' 			=> 'US'
		) );

		/* Create a campaign wth a donation from James Gordon */
		Charitable_Donation_Helper::create_donation( array(
			'user_id'			=> $this->james_gordon->ID, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> Charitable_Campaign_Helper::create_campaign(),
					'amount'		=> 100
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal',
			'note'				=> 'This is a note'	
		) );
	}	

	public function test_get_user_id() {
		$this->assertGreaterThan( 0, $this->james_gordon->ID );
	}

	/**
	 * @depends test_get_user_id
	 */
	public function test_get_donor() {
		$this->assertInternalType( 'object', $this->james_gordon->get_donor() );
	}

	/**
	 * @depends test_get_donor
	 */
	public function test_is_donor() {
		$this->assertTrue( $this->james_gordon->is_donor() );
	}

	/** 
	 * @depends test_is_donor
	 */
	public function test_is_donor_with_non_donor() {
		$user_id = $this->factory->user->create( array( 
			'user_email'		=> 'carmine@gotham.com',
			'first_name'		=> 'Carmine', 
			'last_name'			=> 'Falcone' 
		) );

		$user = new Charitable_User( $user_id );
		$this->assertFalse( $user->is_donor() );
	}	

	/** 
	 * @depends test_is_donor
	 */
	public function test_is_donor_with_non_wpuser() {
		$donor_id = charitable_get_table( 'donors' )->insert( array( 
			'email'			=> 'fish.mooney@gotham.com',
			'first_name'	=> 'Fish', 
			'last_name'		=> 'Mooney'
		) );

		$user = Charitable_User::init_with_donor( $donor_id );
		$this->assertTrue( $user->is_donor() );
	}

	/** 
	 * @depends test_get_donor
	 */
	public function test_get_name() {
		$this->assertEquals( 'James Gordon', $this->james_gordon->get_name() );
	}

	/** 
	 * @depends test_get_donor
	 */
	public function test_get_user_email() {
		$this->assertEquals( 'james@gotham.com', $this->james_gordon->get('user_email') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_first_name() {
		$this->assertEquals( 'James', $this->james_gordon->get('first_name') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_last_name() {		
		$this->assertEquals( 'Gordon',  $this->james_gordon->get('last_name') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_address() {
		$this->assertEquals( '22 Batman Avenue', $this->james_gordon->get('donor_address') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_address_2() {
		$this->assertEquals( '', $this->james_gordon->get('donor_address_2') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_city() {
		$this->assertEquals( 'Gotham', $this->james_gordon->get('donor_city') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_state() {
		$this->assertEquals( 'Gotham State', $this->james_gordon->get('donor_state') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_postcode() {
		$this->assertEquals( '29292', $this->james_gordon->get('donor_postcode') );
	}

	/** 
	 * @depends test_get_user_email
	 */
	public function test_get_donor_country() {
		$this->assertEquals( 'US', $this->james_gordon->get('donor_country') );
	}

	/** 
	 * @depends test_get_donor
	 */
	public function test_get_address_fields() {
		$this->assertCount( 6, $this->james_gordon->get_address_fields() );
	}

	/**
	 * @depends test_get_address_fields
	 */	
	public function test_get_address() {
		$expected = "James Gordon<br/>22 Batman Avenue<br/>Gotham, GOTHAM STATE 29292<br/>United States (US)";
		$this->assertEquals( $expected, $this->james_gordon->get_address() );
	}	

	/** 
	 * @depends test_get_donor
	 */
	public function test_get_donations() {
		$this->assertCount( 1, $this->james_gordon->get_donations() );
	}

	/**
	 * @depends test_get_donations
	 */
	public function test_get_total_donated() {
		$this->assertEquals( 100.00, $this->james_gordon->get_total_donated() );
		$this->assertInternalType( 'float', $this->james_gordon->get_total_donated() );
	}
}