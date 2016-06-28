<?php

/**
 * Contains tests for functions added in `includes/public/charitable-page-functions.php`.
 */

class Test_Charitable_Page_Functions extends WP_UnitTestCase {

	private static $campaign_id;

	private static $donation_id;

	public function setUp() {
		parent::setUp();

		self::$campaign_id = Charitable_Campaign_Helper::create_campaign();

		self::$donation_id = Charitable_Donation_Helper::create_donation( array(
			'campaigns' => array(
				array(
					'campaign_id' => self::$campaign_id,
					'amount' => 50,
					'campaign_name' => 'Test Campaign',
				),
			),
			'user' => array(
				'first_name' => 'Matthew',
				'last_name' => 'Murdoch',
				'email' => 'matthew.murdoch@example.com',
			),
		) );

		/**
		 * Temporary workaround for issue noted below.
		 * @see https://core.trac.wordpress.org/ticket/37207
		 */
		Charitable_Post_Types::get_instance()->add_endpoints();
	}

	/**
	 * @covers charitable_template
	 */
	// public function test_charitable_template() {

	// 	ob_start();

	// 	$this->assertInstanceOf( 'Charitable_Template', charitable_template( 'custom-styles.css.php' ) );

	// 	ob_clean();

	// }

	/**
	 * @covers charitable_get_template_path
	 */
	public function test_get_template_path() {

		$expected = charitable()->get_path( 'templates', true ) . 'campaign-loop.php';

		$this->assertEquals( $expected, charitable_get_template_path( 'campaign-loop.php' ) );

	}

	/**
	 * @covers charitable_is_campaign_donation_page
	 */
	public function test_is_campaign_donation_page() {

		$page = charitable_get_campaign_donation_page_permalink( false, array( 'campaign_id' => self::$campaign_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_campaign_donation_page() );

	}

	/**
	 * @covers charitable_is_campaign_widget_page
	 */
	public function test_is_campaign_widget_page() {

		$page = charitable_get_campaign_widget_page_permalink( false, array( 'campaign_id' => self::$campaign_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_campaign_widget_page() );

	}

	/**
	 * @covers charitable_is_donation_receipt_page
	 */
	public function test_is_donation_receipt_page() {

		$page = charitable_get_donation_receipt_page_permalink( false, array( 'donation_id' => self::$donation_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_donation_receipt_page() );

	}

	/**
	 * @covers charitable_is_donation_processing_page
	 */
	public function test_is_donation_processing_page() {

		$page = charitable_get_donation_processing_page_permalink( false, array( 'donation_id' => self::$donation_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_donation_processing_page() );

	}

	/**
	 * @covers charitable_is_donation_cancel_page
	 */
	public function test_charitable_is_donation_cancel_page() {

		$page = charitable_get_donation_cancel_page_permalink( false, array( 'donation_id' => self::$donation_id ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_campaign_donation_page() );

	}

	/**
	 * @covers charitable_is_email_preview
	 */
	public function test_is_email_preview() {

		$page = esc_url_raw( add_query_arg( array(
			'charitable_action' => 'preview_email', 
			'email_id' => 'campaign_end',
		), home_url() ) );

		$this->go_to( $page );

		$this->assertTrue( charitable_is_email_preview() );

	}
}
