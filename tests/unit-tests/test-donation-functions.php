<?php

/**
 * Contains tests for functions added in `includes/donations/charitable-donation-functions.php`.
 */

class Test_Charitable_Donation_Functions extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	/**
	 * @covers charitable_create_donation
	 */
	public function test_create_donation() {

		$donation_id = $this->create_donation();

		$this->assertGreaterThan( 0, $donation_id );

	}

	/**
	 * @covers charitable_create_donation
	 * @depends test_create_donation
	 */
	public function test_create_donation_correct_gateway() {

		$donation_id = $this->create_donation();

		$this->assertEquals( 'manual', charitable_get_donation_gateway( $donation_id ) );

	}

	/**
	 * @covers charitable_create_donation
	 * @depends test_create_donation
	 */
	public function test_create_donation_correct_amount() {

		$donation_id = $this->create_donation();

		$this->assertEquals( 50, charitable_get_donation( $donation_id )->get_total_donation_amount( false ) );

	}

	/**
	 * @covers charitable_create_donation
	 * @depends test_create_donation
	 */
	public function test_create_donation_correct_user() {

		$donation_id = $this->create_donation();

		$this->assertEquals( 'Matthew Murdoch', charitable_get_donation( $donation_id )->get_donor() );

	}

	/**
	 * @covers charitable_cancel_donation
	 * @depends test_create_donation
	 */
	private function test_cancel_donation() {

		$donation_id = $this->create_donation( 'charitable-pending' );

		set_query_var( 'cancel', true );
		set_query_var( 'donation_id', $donation_id );

		$this->assertTrue( charitable_cancel_donation() );

	}

	/**
	 * @covers charitable_cancel_donation
	 * @depends test_cancel_donation
	 */
	private function test_do_not_cancel_donation() {

		$donation_id = $this->create_donation( 'charitable-pending' );

		$this->assertFalse( charitable_cancel_donation() );

	}

	/**
	 * Create a donation using `charitable_create_donation()`
	 *
	 * @param 	string $status
	 * @return  int The donation ID
	 */
	private function create_donation( $status = 'charitable-completed' ) {

		$campaign_id = Charitable_Campaign_Helper::create_campaign();

		$args = array(
			'status' => $status,
			'gateway' => 'manual',
			'note' => '',
			'campaigns' => array(
				array(
					'campaign_id' => $campaign_id,
					'amount' => 50,
					'campaign_name' => 'Test Campaign',
				),
			),
			'user' => array(
				'first_name' => 'Matthew',
				'last_name' => 'Murdoch',
				'email' => 'matthew.murdoch@example.com',
			),
		);

		return charitable_create_donation( $args );

	}
}
