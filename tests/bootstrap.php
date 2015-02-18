<?php
$_tests_dir = getenv('WP_TESTS_DIR');

// Look for a wordpress-tests-lib directory on the same level as the WordPress installation.
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

if ( ! defined( 'COOKIE_DOMAIN' ) ) {
	define( 'COOKIE_DOMAIN', false );
}

if ( !defined('COOKIEPATH') ) {
	define('COOKIEPATH', 'charitable.test' );
}

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../charitable.php';	
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

activate_plugin( 'charitable/charitable.php' );

echo "Installing Charitable...\n";

// Install Charitable
charitable()->activate();

global $current_user;

$current_user = new WP_User(1);
$current_user->set_role('administrator');


require 'helpers/charitable-campaign-helper.php';
require 'helpers/charitable-donation-helper.php';
require 'helpers/charitable-donor-helper.php';