<?php

class Test_Charitable_Public extends WP_UnitTestCase {

	private $charitable_public;

	function setUp() {
		parent::setUp();
		$this->charitable_public = charitable()->get_registered_object( 'Charitable_Public' );
		$this->directory_path = charitable()->get_path( 'public' );
		$this->directory_url = charitable()->get_path( 'public', false );
	}

	function test_load_dependencies() {
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'class-charitable-template.php' );
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'class-charitable-template-part.php' );
		$this->assertFileExists( charitable()->get_path( 'public' ) . 'class-charitable-templates.php' );
	}

	function test_attach_hooks_and_filters() {
		$this->assertEquals( 5, has_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ) ) );
		$this->assertEquals( 5, has_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ) ) );
		$this->assertEquals( 10, has_action('wp_enqueue_scripts', array( $this->charitable_public, 'wp_enqueue_scripts' ) ) );		
	}
}