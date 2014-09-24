<?php
$_tests_dir = getenv('WP_TESTS_DIR');

// Look for a wordpress-tests-lib directory on the same level as the WordPress installation.
if ( !$_tests_dir ) $_tests_dir = dirname(__FILE__) . '/../../../../../wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../charitable.php';	
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

// deactivate_plugins('charitable/charitable.php');
// activate_plugin('charitable/charitable.php');

require 'framework/testcase.php';