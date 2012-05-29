<?php
/**
 * Runs unit tests.
 *
 * @package Elgg
 * @subpackage Test
 */


require_once(dirname( __FILE__ ) . '/../start.php');

admin_gatekeeper();

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

$test_files = array();

if (($dir = (string) get_input('dir')) && preg_match('~^\\w+(/\\w+)*$~', $dir)) {
	if ($dir === 'test_files') {
		exit ("The given directory cannot be run as a test suite.");
	}
	$path = dirname(__FILE__) . "/$dir";
	if (is_dir($path)) {
		$iter = new RecursiveDirectoryIterator($path);
		foreach ($iter as $fileInfo) {
			/* @var SplFileInfo $fileInfo */
			$basename = $fileInfo->getBasename();
			if ($fileInfo->isFile() && preg_match('/^\\w+\\.php$/', $basename)) {
				$test_files[] = $fileInfo->getPathname();
			}
		}
	} else {
		exit ("Test directory not found: $dir");
	}
	if ($test_files) {
		$title = "Elgg Unit Tests in $dir";
	} else  {
		exit ("No test files found in: $dir");
	}
} else {
	$title = 'Elgg Core Unit Tests';
	// emit a hook to pull in all tests
	$test_files = elgg_trigger_plugin_hook('unit_test', 'system', null, array());
}

$suite = new TestSuite($title);

foreach ($test_files as $file) {
	$suite->addFile($file);
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


$old = elgg_set_ignore_access(TRUE);
$suite->Run(new HtmlReporter('utf-8'));
elgg_set_ignore_access($old);
