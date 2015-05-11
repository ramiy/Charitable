<?php

class Test_Charitable extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		$this->charitable = charitable();
		$this->directory_path = $this->charitable->get_path( 'directory' );
		$this->directory_url = $this->charitable->get_path( 'directory', false );
	}

	function test_static_instance() {
		$this->assertClassHasStaticAttribute( 'instance', get_class( $this->charitable ) );
	}

	function test_load_dependencies() {
		$this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-start-object.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-addons.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-roles.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-actions.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-post-types.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-campaigns.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-widgets.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-gateway.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'interface-charitable-donation-form.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-form.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-campaign.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-form.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donation-form-hidden.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donations.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-user.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donor.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-donor-query.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'db/abstract-class-charitable-db.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'db/class-charitable-campaign-donations-db.php' );
        $this->assertFileExists( $this->charitable->get_path( 'public' ) . 'class-charitable-session.php' );
        $this->assertFileExists( $this->charitable->get_path( 'public' ) . 'class-charitable-session-donation.php' );
        $this->assertFileExists( $this->charitable->get_path( 'public' ) . 'class-charitable-template.php' );      
        $this->assertFileExists( $this->charitable->get_path( 'public' ) . 'class-charitable-template-part.php' );
        $this->assertFileExists( $this->charitable->get_path( 'public' ) . 'class-charitable-templates.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-currency.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-request.php' );      
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-locations.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'class-charitable-notices.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'charitable-core-functions.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'charitable-utility-functions.php' );
        $this->assertFileExists( $this->charitable->get_path( 'includes' ) . 'charitable-template-functions.php' );
	}

	function test_attach_hooks_and_filters() {
		$this->assertEquals( 3, has_action('charitable_start', array( 'Charitable_Donation_Actions', 'charitable_start' ) ) );
		$this->assertEquals( 3, has_action('charitable_start', array( 'Charitable_Post_Types', 'charitable_start' ) ) );		
		$this->assertEquals( 3, has_action('charitable_start', array( 'Charitable_Widgets', 'charitable_start' ) ) );
		$this->assertEquals( 3, has_action('charitable_start', array( 'Charitable_Gateway', 'charitable_start' ) ) );
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
		$this->assertEquals( $this->directory_path . 'charitable.php', 	$this->charitable->get_path() ); // __FILE__
		$this->assertEquals( $this->directory_path, 					$this->charitable->get_path( 'directory' ) );
		$this->assertEquals( $this->directory_url, 						$this->charitable->get_path( 'directory', false ) );
		$this->assertEquals( $this->directory_path . 'includes/', 		$this->charitable->get_path( 'includes' ) );
		$this->assertEquals( $this->directory_path . 'includes/admin/', $this->charitable->get_path( 'admin' ) );
		$this->assertEquals( $this->directory_path . 'includes/public/',$this->charitable->get_path( 'public' ) );		
		$this->assertEquals( $this->directory_path . 'assets/',			$this->charitable->get_path( 'assets' ) );
		$this->assertEquals( $this->directory_path . 'templates/',		$this->charitable->get_path( 'templates' ) );
	}

	function test_get_location_helper() {
		$this->assertInstanceOf( 'Charitable_Locations', $this->charitable->get_location_helper() );
	}

	function test_get_currency_helper() {
		$this->assertInstanceOf( 'Charitable_Currency', $this->charitable->get_currency_helper() );
	}

	function test_get_request() {
		$this->assertEquals( 'Charitable_Request', get_class( $this->charitable->get_request() ) );
	}

	function test_is_activation() {
		$this->assertFalse( $this->charitable->is_activation() );
	}

	function test_is_deactivation() {
		$this->assertFalse( $this->charitable->is_deactivation() );
	}
}