<?php

class Test_Charitable extends WP_UnitTestCase {

	private $charitable;

	function setUp() {
		parent::setUp();

		$this->charitable = get_charitable();
		$this->directory_path = $this->charitable->get_directory_path();
		$this->directory_url = $this->charitable->get_directory_url();

	}

	function test_static_instance() {
		$this->assertClassHasStaticAttribute( 'instance', get_class( $this->charitable ) );
	}

	function test_include_files() {
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-donation-controller.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-post-types.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-query.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-templates.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-widgets.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'interface-charitable-donation-form.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-campaign.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-donation.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-donation-form.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-donation-form-hidden.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-currency-helper.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-request.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-template.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-template-part.php' );
		$this->assertFileExists( $this->charitable->get_includes_path() . 'class-charitable-location-helper.php' );
	}

	function test_attach_hooks_and_filters() {
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Donation_Controller', 'charitable_start' ) ) );
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Post_Types', 'charitable_start' ) ) );
		$this->assertEquals( 2, has_action('charitable_start', array( 'Charitable_Query', 'charitable_start' ) ) );
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

	function test_get_includes_path() {
		$this->assertEquals( $this->directory_path . 'includes/', $this->charitable->get_includes_path() );
	}

	function test_get_admin_path() {
		$this->assertEquals( $this->directory_path . 'includes/admin/', $this->charitable->get_admin_path() );
	}

	function test_get_assets_path() {
		$this->assertEquals( $this->directory_url . 'assets/', $this->charitable->get_assets_path() );
	}

	function test_get_theme_template_path() {
		$this->assertEquals( 'charitable', $this->charitable->get_theme_template_path() );
	}

	function test_get_plugin_template_path() {
		$this->assertEquals( $this->directory_path . 'templates/', $this->charitable->get_plugin_template_path() );
	}

	function test_get_location_helper() {
		$this->assertEquals( 'Charitable_Location_Helper', get_class( $this->charitable->get_location_helper() ) );
	}

	function test_get_request() {
		$this->assertEquals( 'Charitable_Request', get_class( $this->charitable->get_request() ) );
	}

}