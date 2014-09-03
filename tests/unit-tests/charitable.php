<?php

class Test_Charitable extends Charitable_UnitTestCase {

	function setUp() {
		parent::setUp();
		$this->directory_path = $this->charitable->get_path();
		$this->directory_url = $this->charitable->get_path('', false);
	}

	function test_static_instance() {
		$this->assertClassHasStaticAttribute( 'instance', get_class( $this->charitable ) );
	}

	function test_include_files() {
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-controller.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-post-types.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-campaign-query.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-templates.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-widgets.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'interface-charitable-donation-form.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-campaign.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-form.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-form-hidden.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-currency-helper.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-request.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-template.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-template-part.php' );
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-location-helper.php' );
	}

	function test_attach_hooks_and_filters() {
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Donation_Controller', 'charitable_start' ) ) );
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Post_Types', 'charitable_start' ) ) );
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ) ) );
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Widgets', 'charitable_start' ) ) );
	}

	function test_is_start() {
		$this->assertFalse( $this->charitable->is_start() );
	}

	function test_started() {
		$this->assertTrue( $this->charitable->started() );
	}

	function test_register_object() {
		$this->assertFalse( $this->charitable->get_registered_object('Test_Charitable') );
		$this->charitable->register_object( $this );
		$this->assertEquals( 'Test_Charitable', get_class( $this->charitable->get_registered_object('Test_Charitable') ) );
	}

	function test_get_path() {
		$this->assertEquals( $this->directory_path, 					$this->charitable->get_path() );
		$this->assertEquals( $this->directory_url, 						$this->charitable->get_path( '', false ) );
		$this->assertEquals( $this->directory_path . 'includes/', 		$this->charitable->get_path( 'includes' ) );
		$this->assertEquals( $this->directory_path . 'includes/admin/', $this->charitable->get_path( 'admin' ) );
		$this->assertEquals( $this->directory_path . 'assets/', 		$this->charitable->get_path( 'assets' ) );
		$this->assertEquals( $this->directory_url . 'assets/', 			$this->charitable->get_path( 'assets', false ) );
		$this->assertEquals( 'charitable', 								$this->charitable->get_path( 'theme_templates' ) );
		$this->assertEquals( $this->directory_path . 'templates/', 		$this->charitable->get_path( 'plugin_templates' ) );
	}

	function test_get_location_helper() {
		$this->assertEquals( 'Charitable_Location_Helper', get_class( $this->charitable->get_location_helper() ) );
	}

	function test_get_request() {
		$this->assertEquals( 'Charitable_Request', get_class( $this->charitable->get_request() ) );
	}

}