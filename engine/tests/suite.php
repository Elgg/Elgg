<?php
/**
 * Runs unit tests.
 * 
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */


require_once( dirname( __FILE__ ) . '/../start.php' );

$vendor_path = "$CONFIG->path/vendors/simpletest";
$test_path = "$CONFIG->path/engine/tests";

require_once( "$vendor_path/unit_tester.php" );
require_once( "$vendor_path/mock_objects.php" );
require_once( "$vendor_path/reporter.php" );
require_once( "$test_path/elgg_unit_test.php" );

$suite = new TestSuite( 'Elgg Core Unit Tests' );

// emit a hook to pull in all tests
$test_files = trigger_plugin_hook('unit_test', 'system', null, array());
foreach ($test_files as $file) {
	$suite->addTestFile($file);
}

if (!$CONFIG->debug) {
	if ( TextReporter::inCli() )
	{
	    exit( $suite->Run( new TextReporter() ) ? 0 : 1 );
	}
	$suite->Run( new HtmlReporter() );
}
