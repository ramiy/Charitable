<?php

class Test_Charitable_Session_Donation extends Charitable_UnitTestCase {

	private $session_donation;

	function setUp() {
		parent::setUp();		

		$this->session_donation = new Charitable_Session_Donation();

		$this->session_donation->set( 'campaign_id', 43 );
		$this->session_donation->set( 'campaign_name', 'Test Campaign 1' );
		$this->session_donation->set( 'amount', '53.50' );
	}	

	function test_get() {
		$this->assertEquals( 43, $this->session_donation->get( 'campaign_id' ) );
		$this->assertEquals( 'Test Campaign 1', $this->session_donation->get( 'campaign_name' ) );
		$this->assertEquals( 53.50, $this->session_donation->get( 'amount' ) );
	}
}