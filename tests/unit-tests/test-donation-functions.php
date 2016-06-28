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
	public function test_cancel_donation() {

		/**
		 * Temporary workaround for issue noted below.
		 * @see https://core.trac.wordpress.org/ticket/37207
		 */
		Charitable_Post_Types::get_instance()->add_endpoints();

		$donation_id = $this->create_donation( 'charitable-pending' );

		$page = charitable_get_donation_cancel_page_permalink( false, array( 'donation_id' => $donation_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_cancel_donation() );

	}

	/**
	 * @covers charitable_cancel_donation
	 * @depends test_cancel_donation
	 */
	public function test_do_not_cancel_donation() {

		/**
		 * Temporary workaround for issue noted below.
		 * @see https://core.trac.wordpress.org/ticket/37207
		 */
		Charitable_Post_Types::get_instance()->add_endpoints();

		$donation_id = $this->create_donation( 'charitable-pending' );

		$campaign_donation = current( charitable_get_donation( $donation_id )->get_campaign_donations() );

		$donate_url = charitable_get_campaign_donation_page_permalink( false, array( 'campaign_id' => $campaign_donation->campaign_id ) );

		$this->go_to( $donate_url );

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
