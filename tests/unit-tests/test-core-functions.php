<?php

class Test_Charitable_Core_Functions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
	}

	function test_charitable() {
		$this->assertInstanceOf( 'Charitable', charitable() );
	}
	
	function test_charitable_get_option() {
		$this->assertFalse( charitable_get_option( 'nonexistentkey' ) );
		// charitable_get_option( 'defaultgateway' );
	}
	
	function test_charitable_get_helper() {
		$this->assertInstanceOf( 'Charitable_Gateway', charitable_get_helper( 'gateway' ) );
	}
	
	function test_charitable_get_notices() {
		$this->assertInstanceOf( 'Charitable_Notices', charitable_get_notices() );
	}
	
	function test_charitable_get_location_helper() {
		$this->assertInstanceOf( 'Charitable_Locations', charitable_get_location_helper() );
	}
	
	function test_charitable_get_session() {
		$this->assertInstanceOf( 'Charitable_Session', charitable_get_session() );
	}	
}