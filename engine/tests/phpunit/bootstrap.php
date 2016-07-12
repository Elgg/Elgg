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
$CONFIG = (object) [
	'Config_file' => false,
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'path' => __DIR__ . '/../../../',
	'dataroot' => __DIR__ . '/test_files/dataroot/',
	'cacheroot' => __DIR__ . '/test_files/cacheroot/',
	'site_guid' => 1,
	'AutoloaderManager_skip_storage' => true,
	'simplecache_enabled' => false,
	'system_cache_enabled' => false,
	'Elgg\Application_phpunit' => true,
	// \Elgg\Config::get() falls back to loading config values from database
	// for undefined keys. This flag ensures we do not attempt reading data
	// from database during tests
	'site_config_loaded' => true,
	'icon_sizes' => array(
		'topbar' => array('w' => 16, 'h' => 16, 'square' => true, 'upscale' => true),
		'tiny' => array('w' => 25, 'h' => 25, 'square' => true, 'upscale' => true),
		'small' => array('w' => 40, 'h' => 40, 'square' => true, 'upscale' => true),
		'medium' => array('w' => 100, 'h' => 100, 'square' => true, 'upscale' => true),
		'large' => array('w' => 200, 'h' => 200, 'square' => false, 'upscale' => false),
		'master' => array('w' => 550, 'h' => 550, 'square' => false, 'upscale' => false),
	),
];

global $_ELGG;
$_ELGG = (object) [
	'view_path' => __DIR__ . '/../../../views/',
	'allowed_ajax_views' => [],
];

function _elgg_testing_config(\Elgg\Config $config = null) {
	static $inst;
	if ($config) {
		$inst = $config;
	}
	return $inst;
}

/**
 * Create an HTTP request
 *
 * @param string $uri             URI of the request
 * @param string $method          HTTP method
 * @param array  $parameters      Query/Post parameters
 * @param int    $ajax            AJAX api version (0 for non-ajax)
 * @param bool   $add_csrf_tokens Add CSRF tokens
 * @return \Elgg\Http\Request
 */
function _elgg_testing_request($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
	$site_url = elgg_get_site_url();
	$path = substr(elgg_normalize_url($uri), strlen($site_url));
	$path_key = \Elgg\Application::GET_PATH_KEY;

	if ($add_csrf_tokens) {
		$ts = time();
		$parameters['__elgg_ts'] = $ts;
		$parameters['__elgg_token'] = _elgg_services()->actions->generateActionToken($ts);
	}

	$request = \Elgg\Http\Request::create("?$path_key=" . urlencode($path), $method, $parameters);

	$cookie_name = _elgg_services()->config->getCookieConfig()['session']['name'];
	$session_id = _elgg_services()->session->getId();
	$request->cookies->set($cookie_name, $session_id);

	$request->headers->set('Referer', elgg_normalize_url('phpunit'));

	if ($ajax) {
		$request->headers->set('X-Requested-With', 'XMLHttpRequest');
		if ($ajax >= 2) {
			$request->headers->set('X-Elgg-Ajax-API', (string) $ajax);
		}
	}

	return $request;
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

	global $GLOBALS;
	$GLOBALS['DEFAULT_FILE_STORE'] = new \ElggDiskFilestore($CONFIG->dataroot);

	_elgg_testing_application($app);
});
