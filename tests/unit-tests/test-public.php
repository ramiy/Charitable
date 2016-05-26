<?php

class Test_Charitable_Public extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();		
	}

	function test_load_dependencies() {
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-functions.php' );
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'charitable-template-hooks.php' );
	}

	function test_hooks() {
		$this->assertEquals( 10, has_action( 'after_setup_theme', array( Charitable_Public::get_instance(), 'load_template_files' ) ) );
        $this->assertEquals( 10, has_action( 'wp_enqueue_scripts', array( Charitable_Public::get_instance(), 'wp_enqueue_scripts') ) );
        $this->assertEquals( 10, has_filter( 'post_class', array( Charitable_Public::get_instance(), 'campaign_post_class' ) ) );
        $this->assertEquals( 10, has_filter( 'comments_open', array( Charitable_Public::get_instance(), 'disable_comments_on_application_pages' ) ) );
	}
}