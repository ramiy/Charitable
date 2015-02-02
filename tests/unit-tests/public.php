<?php

class Test_Charitable_Public extends Charitable_UnitTestCase {

	private $charitable_public;

	function setUp() {
		parent::setUp();
		$this->charitable_public = $this->charitable->get_registered_object( 'Charitable_Public' );
		$this->directory_path = $this->charitable->get_path( 'public' );
		$this->directory_url = $this->charitable->get_path( 'public', false );
	}

	function test_load_dependencies() {
		$this->assertFileExists( $this->charitable_public->get_path( 'includes' ) . 'class-charitable-template.php' );
		$this->assertFileExists( $this->charitable_public->get_path( 'includes' ) . 'class-charitable-template-part.php' );
		$this->assertFileExists( $this->charitable_public->get_path( 'includes' ) . 'class-charitable-templates.php' );
	}

	function test_attach_hooks_and_filters() {
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ) ) );
		$this->assertEquals( 10, has_action('wp_enqueue_scripts', array( $this->charitable_public, 'wp_enqueue_scripts' ) ) );		
	}

	function test_get_path() {
		$this->assertEquals( $this->directory_path, $this->charitable_public->get_path() );
		$this->assertEquals( $this->directory_url, $this->charitable_public->get_path( '', false ) );
		$this->assertEquals( $this->charitable->get_path( 'includes' ), $this->charitable_public->get_path( 'includes' ) );
		$this->assertEquals( $this->directory_path . 'assets/', $this->charitable_public->get_path( 'assets' ) );
		$this->assertEquals( $this->directory_url . 'assets/', $this->charitable_public->get_path( 'assets', false ) );
		$this->assertEquals( 'charitable', $this->charitable_public->get_path( 'theme_templates' ) );
		$this->assertEquals( $this->directory_path . 'templates/', $this->charitable_public->get_path( 'base_templates' ) );
	}

}