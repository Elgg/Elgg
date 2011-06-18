<?php
	/**
	 * Elgg diagnostics language pack.
	 *
	 * @package ElggDiagnostics
	 */

	$english = array(

			'admin:utilities:diagnostics' => 'System Diagnostics',
			'diagnostics' => 'System diagnostics',
			'diagnostics:report' => 'Diagnostics Report',
			'diagnostics:unittester' => 'Unit Tests',

			'diagnostics:description' => 'The following diagnostic report is useful for diagnosing any problems with Elgg, and should be attached to any bug reports you file.',
			'diagnostics:unittester:description' => 'The following are diagnostic tests which are registered by plugins and may be performed in order to debug parts of the Elgg framework.',

			'diagnostics:unittester:description' => 'Unit tests check Elgg Core for broken or buggy APIs.',
			'diagnostics:unittester:debug' => 'The site must be in debug mode to run unit tests.',
			'diagnostics:unittester:warning' => 'WARNING: These tests can leave behind debugging objects in your database.<br />DO NOT USE ON A PRODUCTION SITE!',

			'diagnostics:test:executetest' => 'Execute test',
			'diagnostics:test:executeall' => 'Execute All',
			'diagnostics:unittester:notests' => 'Sorry, there are no unit test modules currently installed.',
			'diagnostics:unittester:testnotfound' => 'Sorry, the report could not be generated because that test was not found',

			'diagnostics:unittester:testresult:nottestclass' => 'FAIL - Result not a test class',
			'diagnostics:unittester:testresult:fail' => 'FAIL',
			'diagnostics:unittester:testresult:success' => 'SUCCESS',

			'diagnostics:unittest:example' => 'Example unit test, only available in debug mode.',

			'diagnostics:unittester:report' => 'Test report for %s',

			'diagnostics:download' => 'Download',


			'diagnostics:header' => '========================================================================
Elgg Diagnostic Report
Generated %s by %s
========================================================================

',
			'diagnostics:report:basic' => '
Elgg Release %s, version %s

------------------------------------------------------------------------',
			'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
			'diagnostics:report:plugins' => '
Installed plugins and details:

%s
------------------------------------------------------------------------',
			'diagnostics:report:md5' => '
Installed files and checksums:

%s
------------------------------------------------------------------------',
			'diagnostics:report:globals' => '
Global variables:

%s
------------------------------------------------------------------------',

	);

	add_translation("en",$english);
?>