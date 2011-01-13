<?php
/**
 * Runs unit tests.
 *
 * @package Elgg
 * @subpackage Test
 */


require_once(dirname( __FILE__ ) . '/../start.php');

$vendor_path = "$CONFIG->path/vendors/simpletest";
$test_path = "$CONFIG->path/engine/tests";

require_once("$vendor_path/unit_tester.php");
require_once("$vendor_path/mock_objects.php");
require_once("$vendor_path/reporter.php");
require_once("$test_path/elgg_unit_test.php");

// turn off system log
elgg_unregister_event_handler('all', 'all', 'system_log_listener');
elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');

// Disable maximum execution time.
// Tests take a while...
set_time_limit(0);

$suite = new TestSuite('Elgg Core Unit Tests');

// emit a hook to pull in all tests
$test_files = elgg_trigger_plugin_hook('unit_test', 'system', null, array());
foreach ($test_files as $file) {
	$suite->addTestFile($file);
}

// Only run tests in debug mode.
if (!isset($CONFIG->debug)) {
	exit ('The site must be in debug mode to run unit tests.');
}

if (TextReporter::inCli()) {
	// In CLI error codes are returned: 0 is success
	elgg_set_ignore_access(TRUE);
	exit ($suite->Run(new TextReporter()) ? 0 : 1 );
}

// Ensure that only logged-in users can see this page
//admin_gatekeeper();
$old = elgg_set_ignore_access(TRUE);
$suite->Run(new HtmlReporter('utf-8'));
elgg_set_ignore_access($old);
