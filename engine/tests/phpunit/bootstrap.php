<?php

use Zend\Mail\Transport\InMemory as InMemoryTransport;

require_once __DIR__ . '/../../../vendor/autoload.php';

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
	'Config_file' => false,
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'path' => __DIR__ . '/../../../',
	'dataroot' => __DIR__ . '/test_files/dataroot/',
	'site_guid' => 1,
	'AutoloaderManager_skip_storage' => true,
	'simplecache_enabled' => false,
	'Elgg\Application_phpunit' => true,
];

global $_ELGG;
$_ELGG = (object)[
	'view_path' => __DIR__ . '/../../../views/',
];

function _elgg_testing_config(\Elgg\Config $config = null) {
	static $inst;
	if ($config) {
		$inst = $config;
	}
	return $inst;
}

// PHPUnit will serialize globals between tests, so let's not introduce any globals here.
call_user_func(function () use ($CONFIG) {
	$config = new \Elgg\Config($CONFIG);
	_elgg_testing_config($config);
	
	$sp = new \Elgg\Di\ServiceProvider($config);

	$sp->setValue('mailer', new InMemoryTransport());

	$sp->siteSecret->setTestingSecret('z1234567890123456789012345678901');

	$app = new \Elgg\Application($sp);
	$app->loadCore();

	// persistentLogin service needs this set to instantiate without calling DB
	_elgg_services()->config->getCookieConfig();

	_elgg_testing_application($app);
});
