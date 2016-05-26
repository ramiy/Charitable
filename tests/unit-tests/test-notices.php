<?php

class Test_Charitable_Notices extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		$this->notices = charitable_get_notices();

		// Clear out all existing notices
		$this->notices->clear();
	}

	function test_get_instance() {
		$this->assertEquals( $this->notices, Charitable_Notices::get_instance() );
	}

	function test_get_errors() {
		$this->notices->add_error( 'Error #1' );
		$this->notices->add_error( 'Error #2' );

		$notices = $this->notices->get_errors();

		$this->assertCount( 2, $notices );
		$this->assertEquals( 'Error #1', $notices[0] );
		$this->assertEquals( 'Error #2', $notices[1] );
	}

	function test_get_warnings() {
		$this->notices->add_warning( 'Warning #1' );

		$notices = $this->notices->get_warnings();
		$this->assertCount( 1, $notices );
		$this->assertEquals( 'Warning #1', $notices[0] );
	}

	function test_get_success_notices() {
		$this->notices->add_success( 'Success #1' );

		$notices = $this->notices->get_success_notices();
		$this->assertCount( 1, $notices );
		$this->assertEquals( 'Success #1', $notices[0] );
	}

	function test_get_info_notices() {
		$this->assertCount( 0, $this->notices->get_info_notices() );
	}

	function test_get_notices() {
		$notices = $this->notices->get_notices();
		$this->assertCount( 4, $notices );
		$this->assertArrayHasKey( 'error', $notices );
		$this->assertArrayHasKey( 'warning', $notices );
		$this->assertArrayHasKey( 'success', $notices );
		$this->assertArrayHasKey( 'info', $notices );
	}
}