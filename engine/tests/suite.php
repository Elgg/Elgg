<?php
/**
 * Runs unit tests.
 */

if (php_sapi_name() !== 'cli') {
	exit("Test suite should only be run using command line");
}

require_once __DIR__ . '/../../autoloader.php';

require_once __DIR__ . '/ElggCoreUnitTest.php';
require_once __DIR__ . '/ElggCoreGetEntitiesBaseTest.php';

// Disable maximum execution time.
// Tests take a while...
set_time_limit(0);

\Elgg\Application::start();

ob_start();

$error = 0;

try {
	$cli_opts = getopt('', [
		'config::',
		// path to config file
	]);

	if (!empty($cli_opts['config']) && file_exists($cli_opts['config'])) {
		// File with custom config options for the suite
		require_once $cli_opts['config'];
		echo PHP_EOL . "Loaded custom configuration for the suite." . PHP_EOL;
	}

	$admin = array_shift(elgg_get_admins(['limit' => 1]));
	if (!login($admin)) {
		throw new Exception("Failed to login as administrator.");
	}

	_elgg_config()->debug = 'NOTICE';

	// turn off system log
	elgg_unregister_plugin_hook_handler('all', 'all', 'system_log_listener');
	elgg_unregister_plugin_hook_handler('log', 'systemlog', 'system_log_default_logger');

	elgg_register_plugin_hook_handler('forward', 'all', function () {
		$set = _elgg_services()->systemMessages->loadRegisters();
		foreach ($set as $prop => $values) {
			if (!empty($values)) {
				foreach ($values as $msg) {
					$log = PHP_EOL;
					$log .= $msg;
					$log .= PHP_EOL;
					elgg_log($log, $prop === 'error' ? 'ERROR' : 'NOTICE');
				}
			}
		}
	});

	elgg_register_plugin_hook_handler('send:before', 'http_response', function ($hook, $type, $response) {
		$content = $response->getContent();
		$log = PHP_EOL;
		$log .= 'RESPONSE: ' . print_r($content, true);
		$log .= PHP_EOL;
		elgg_log($log);
	});

	// disable emails
	elgg_set_email_transport(new Zend\Mail\Transport\InMemory());

	// plugins that contain unit tests
	$plugins = [
		'groups',
		'thewire',
		'web_services'
	];

	// activate plugins that are not activated on install
	foreach ($plugins as $key => $id) {
		$plugin = elgg_get_plugin_from_id($id);
		if (!$plugin || $plugin->isActive()) {
			unset($plugins[$key]);
			continue;
		}
		$plugin->activate();
	}

	$suite = new TestSuite('Elgg Core Unit Tests');

	// emit a hook to pull in all tests
	$test_cases = elgg_trigger_plugin_hook('unit_test', 'system', null, []);
	foreach ($test_cases as $file) {
		if (substr($file, -4, 4) === '.php') {
			$suite->addFile($file);
		} else if (class_exists($file)) {
			$suite->add($file);
		}
	}

	// In CLI error codes are returned: 0 is success
	$start_time = microtime(true);

	$reporter = new TextReporter();
	$result = $suite->Run($reporter);

	// deactivate plugins that were activated for test suite
	foreach ($plugins as $key => $id) {
		$plugin = elgg_get_plugin_from_id($id);
		$plugin->deactivate();
	}

	echo PHP_EOL . sprintf("Time: %.2f seconds, Memory: %.2fMb\n",
		microtime(true) - $start_time,
		memory_get_peak_usage() / 1048576.0 // in megabytes
	) . PHP_EOL;

	if (!$result) {
		throw new Exception('One or more tests have failed');
	}
} catch (Exception $e) {
	$error = 1;
	echo("Test suite has failed with " . get_class($e) . ': ' . $e->getMessage());
}

forward();
fwrite(STDOUT, ob_get_clean());
exit($error);
