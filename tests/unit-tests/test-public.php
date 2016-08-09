<?php

class Test_Charitable_Public extends Charitable_UnitTestCase {

	function setUp() {
		parent::setUp();
	}

	function test_load_dependencies() {
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-functions.php' );
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-hooks.php' );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */	
	function test_after_setup_theme_hook() {
		$this->assertEquals( 10, has_action( 'after_setup_theme', array( Charitable_Public::get_instance(), 'load_template_files' ) ) );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */
	function test_wp_enqueue_scripts_hook() {
		$this->assertEquals( 10, has_action( 'wp_enqueue_scripts', array( Charitable_Public::get_instance(), 'setup_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */
	function test_wp_enqueue_donation_form_scripts_hook() {
		$this->assertEquals( 11, has_action( 'wp_enqueue_scripts', array( Charitable_Public::get_instance(), 'maybe_enqueue_donation_form_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */
	function test_charitable_campaign_loop_before_hook() {
		$this->assertEquals( 10, has_action( 'charitable_campaign_loop_before', array( Charitable_Public::get_instance(), 'maybe_enqueue_donation_form_scripts' ) ) );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */
	function test_post_class_hook() {
		$this->assertEquals( 10, has_filter( 'post_class', array( Charitable_Public::get_instance(), 'campaign_post_class' ) ) );
	}

	/**
	 * @covers Charitable_Publis::__construct()
	 */
	function test_comments_open_hook() {
		$this->assertEquals( 10, has_filter( 'comments_open', array( Charitable_Public::get_instance(), 'disable_comments_on_application_pages' ) ) );
	}
}
