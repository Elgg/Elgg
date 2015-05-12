<?php

require_once __DIR__ . '/../../../autoloader.php';

date_default_timezone_set('America/Los_Angeles');

error_reporting(E_ALL | E_STRICT);

/**
 * Get/set an Application for testing purposes
 *
 * @param \Elgg\Application $app Elgg Application
 * @return \Elgg\Application
 */
function _elgg_testing_application(\Elgg\Application $app = null) {
	static $inst;
	if ($app) {
		$inst = $app;
	}
	return $inst;
}

/**
 * This is here as a temporary solution only. Instead of adding more global
 * state to this file as we migrate tests, try to refactor the code to be
 * testable without global state.
 */
global $CONFIG;
$CONFIG = (object)[
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'dataroot' => __DIR__ . '/test_files/dataroot/',
	'site_guid' => 1,
	'AutoloaderManager_skip_storage' => true,
	'simplecache_enabled' => false,
];

$app = new \Elgg\Application(new \Elgg\Di\ServiceProvider(new \Elgg\Config($CONFIG)));
$app->loadCore();
_elgg_testing_application($app);

// persistentLogin service needs this set to instantiate without calling DB
_elgg_configure_cookies($CONFIG);

// PHPUnit will serialize globals between tests, $app contains Closures!
unset($app);
