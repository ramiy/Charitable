<?php

class Test_Charitable extends Charitable_UnitTestCase {

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
        $includes_path = $this->charitable->get_path( 'includes' );

        $this->assertFileExists( $includes_path . 'abstracts/class-charitable-form.php' );
        $this->assertFileExists( $includes_path . 'abstracts/class-charitable-query.php' );
        $this->assertFileExists( $includes_path . 'abstracts/class-charitable-start-object.php' );
        $this->assertFileExists( $includes_path . 'charitable-core-functions.php' );
        $this->assertFileExists( $includes_path . 'charitable-utility-functions.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-locations.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-notices.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-post-types.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-request.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-cron.php' );
        $this->assertFileExists( $includes_path . 'class-charitable-i18n.php' );
        $this->assertFileExists( $includes_path . 'addons/class-charitable-addons.php' );
        $this->assertFileExists( $includes_path . 'campaigns/charitable-campaign-functions.php' );
        $this->assertFileExists( $includes_path . 'campaigns/class-charitable-campaign.php' );
        $this->assertFileExists( $includes_path . 'campaigns/class-charitable-campaigns.php' );
        $this->assertFileExists( $includes_path . 'campaigns/charitable-campaign-hooks.php' );
        $this->assertFileExists( $includes_path . 'currency/charitable-currency-functions.php' );
        $this->assertFileExists( $includes_path . 'currency/class-charitable-currency.php' );
        $this->assertFileExists( $includes_path . 'donations/abstract-charitable-donation.php' );
        $this->assertFileExists( $includes_path . 'donations/interface-charitable-donation-form.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donation-processor.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donation.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donation-factory.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donations.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donation-form.php' );
        $this->assertFileExists( $includes_path . 'donations/class-charitable-donation-amount-form.php' );
        $this->assertFileExists( $includes_path . 'donations/charitable-donation-hooks.php' );
        $this->assertFileExists( $includes_path . 'donations/charitable-donation-functions.php' );
        $this->assertFileExists( $includes_path . 'user-management/charitable-user-management-hooks.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-forgot-password-form.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-password-form.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-profile-form.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-registration-form.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-reset-password-form.php' );
        $this->assertFileExists( $includes_path . 'user-management/class-charitable-user-management.php' );
        $this->assertFileExists( $includes_path . 'users/charitable-user-functions.php' );
        $this->assertFileExists( $includes_path . 'users/class-charitable-user.php' );
        $this->assertFileExists( $includes_path . 'users/class-charitable-roles.php' );
        $this->assertFileExists( $includes_path . 'users/class-charitable-donor.php' );
        $this->assertFileExists( $includes_path . 'users/class-charitable-donor-query.php' );        
        $this->assertFileExists( $includes_path . 'gateways/interface-charitable-gateway.php' );
        $this->assertFileExists( $includes_path . 'gateways/class-charitable-gateways.php' );
        $this->assertFileExists( $includes_path . 'gateways/abstract-class-charitable-gateway.php' );
        $this->assertFileExists( $includes_path . 'gateways/class-charitable-gateway-offline.php' );
        $this->assertFileExists( $includes_path . 'gateways/class-charitable-gateway-paypal.php' );
        $this->assertFileExists( $includes_path . 'emails/interface-charitable-email.php' );
        $this->assertFileExists( $includes_path . 'emails/class-charitable-emails.php' );
        $this->assertFileExists( $includes_path . 'emails/abstract-class-charitable-email.php' );
        $this->assertFileExists( $includes_path . 'emails/class-charitable-email-new-donation.php' );
        $this->assertFileExists( $includes_path . 'emails/class-charitable-email-donation-receipt.php' );
        $this->assertFileExists( $includes_path . 'emails/class-charitable-email-campaign-end.php' );
        $this->assertFileExists( $includes_path . 'emails/charitable-email-hooks.php' );
        $this->assertFileExists( $includes_path . 'db/abstract-class-charitable-db.php' );
        $this->assertFileExists( $includes_path . 'db/class-charitable-campaign-donations-db.php' );
        $this->assertFileExists( $includes_path . 'db/class-charitable-donors-db.php' );
        $this->assertFileExists( $includes_path . 'licensing/class-charitable-licenses.php' );
        $this->assertFileExists( $includes_path . 'licensing/class-charitable-plugin-updater.php' );
        $this->assertFileExists( $includes_path . 'public/charitable-page-functions.php' );
        $this->assertFileExists( $includes_path . 'public/charitable-template-helpers.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-session.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-template.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-template-part.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-templates.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-ghost-page.php' );
        $this->assertFileExists( $includes_path . 'public/class-charitable-user-dashboard.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-campaigns-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-my-donations-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-donation-receipt-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-login-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-registration-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/class-charitable-profile-shortcode.php' );
        $this->assertFileExists( $includes_path . 'shortcodes/charitable-shortcodes-hooks.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-widgets.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-campaign-terms-widget.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-campaigns-widget.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-donors-widget.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-donate-widget.php' );
        $this->assertFileExists( $includes_path . 'widgets/class-charitable-donation-stats-widget.php' );
        $this->assertFileExists( $includes_path . 'admin/customizer/class-charitable-customizer.php' );
        $this->assertFileExists( $includes_path . 'deprecated/charitable-deprecated-functions.php' );
    }

    function test_attach_hooks_and_filters() {
        $this->assertEquals( 100, has_action( 'plugins_loaded', array( $this->charitable, 'charitable_install' ) ) );
        $this->assertEquals( 100, has_action( 'plugins_loaded', array( $this->charitable, 'charitable_start' ) ) );
        $this->assertEquals( 10, has_action( 'setup_theme', array( 'Charitable_Customizer', 'start' ) ) );
        $this->assertEquals( 20, has_action( 'init', array( $this->charitable, 'do_charitable_actions' ) ) );
        $this->assertEquals( 10, has_filter( 'charitable_sanitize_donation_meta', 'charitable_sanitize_donation_meta' ) );
    }

    function test_is_start() {
        $this->assertFalse( $this->charitable->is_start() );
    }

    function test_started() {
        $this->assertTrue( $this->charitable->started() );
    }   

    function test_get_path() {
        $this->assertEquals( $this->directory_path . 'charitable.php', $this->charitable->get_path() ); // __FILE__
        $this->assertEquals( $this->directory_path, $this->charitable->get_path( 'directory' ) );
        $this->assertEquals( $this->directory_url, $this->charitable->get_path( 'directory', false ) );
        $this->assertEquals( $this->directory_path . 'includes/', $this->charitable->get_path( 'includes' ) );
        $this->assertEquals( $this->directory_path . 'includes/admin/', $this->charitable->get_path( 'admin' ) );
        $this->assertEquals( $this->directory_path . 'includes/public/', $this->charitable->get_path( 'public' ) );     
        $this->assertEquals( $this->directory_path . 'assets/', $this->charitable->get_path( 'assets' ) );
        $this->assertEquals( $this->directory_path . 'templates/', $this->charitable->get_path( 'templates' ) );
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