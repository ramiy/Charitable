<?php

class Test_Charitable_Public extends Charitable_UnitTestCase {

	private static $campaign_id;

	private static $donation_id;

	function setUp() {
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

	function test_load_dependencies() {
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-functions.php' );
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-hooks.php' );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_after_setup_theme_hook() {
		$this->assertEquals( 10, has_action( 'after_setup_theme', array( Charitable_Public::get_instance(), 'load_template_files' ) ) );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_wp_enqueue_scripts_hook() {
		$this->assertEquals( 10, has_action( 'wp_enqueue_scripts', array( Charitable_Public::get_instance(), 'setup_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_wp_enqueue_donation_form_scripts_hook() {
		$this->assertEquals( 11, has_action( 'wp_enqueue_scripts', array( Charitable_Public::get_instance(), 'maybe_enqueue_donation_form_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_charitable_campaign_loop_before_hook() {
		$this->assertEquals( 10, has_action( 'charitable_campaign_loop_before', array( Charitable_Public::get_instance(), 'maybe_enqueue_donation_form_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_post_class_hook() {
		$this->assertEquals( 10, has_filter( 'post_class', array( Charitable_Public::get_instance(), 'campaign_post_class' ) ) );
	}

	/**
	 * @covers Charitable_Public::__construct()
	 */
	function test_comments_open_hook() {
		$this->assertEquals( 10, has_filter( 'comments_open', array( Charitable_Public::get_instance(), 'disable_comments_on_application_pages' ) ) );
	}

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	// function test_comments_disabled_on_campaign_donation_page() {

	// 	$this->set_charitable_option( 'donation_form_display', 'separate_page' );

	// 	$page = charitable_get_campaign_donation_page_permalink( false, array( 'campaign_id' => self::$campaign_id ) );

	// 	$this->go_to( $page );

	// 	$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$campaign_id );

	// 	$this->assertFalse( $comments_enabled );

	// }

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	function test_comments_not_disabled_on_campaign_page_in_separate_page_mode() {

		$this->set_charitable_option( 'donation_form_display', 'separate_page' );

		$page = get_permalink( self::$campaign_id );

		$this->go_to( $page );

		$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$campaign_id );

		$this->assertTrue( $comments_enabled );

	}

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	function test_comments_not_disabled_on_campaign_page_in_modal_mode() {

		$this->set_charitable_option( 'donation_form_display', 'modal' );

		$page = get_permalink( self::$campaign_id );

		$this->go_to( $page );

		$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$campaign_id );

		$this->assertTrue( $comments_enabled );

	}

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	function test_comments_not_disabled_on_campaign_page_in_same_page_mode() {

		$this->set_charitable_option( 'donation_form_display', 'same_page' );

		$page = get_permalink( self::$campaign_id );

		$this->go_to( $page );

		$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$campaign_id );

		$this->assertTrue( $comments_enabled );

	}

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	// function test_comments_disabled_on_campaign_widget_page() {

	// 	$this->set_charitable_option( 'donation_form_display', 'separate_page' );

	// 	$page = charitable_get_campaign_widget_page_permalink( false, array( 'campaign_id' => self::$campaign_id ) );

	// 	$this->go_to( $page );

	// 	$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$campaign_id );

	// 	$this->assertFalse( $comments_enabled );

	// }

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	function test_comments_disabled_on_donation_receipt_page() {

		$this->set_charitable_option( 'donation_form_display', 'separate_page' );

		$page = charitable_get_donation_receipt_page_permalink( false, array( 'donation_id' => self::$donation_id ) );

		$this->go_to( $page );

		$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$donation_id );

		$this->assertFalse( $comments_enabled );

	}

	/**
	 * @covers Charitable_Public::disable_comments_on_application_pages()
	 */
	function test_comments_disabled_on_donation_processing_page() {

		$this->set_charitable_option( 'donation_form_display', 'separate_page' );

		$page = charitable_get_donation_processing_page_permalink( false, array( 'donation_id' => self::$donation_id ) );

		$this->go_to( $page );

		$comments_enabled = Charitable_Public::get_instance()->disable_comments_on_application_pages( true, self::$donation_id );

		$this->assertFalse( $comments_enabled );

	}
}
