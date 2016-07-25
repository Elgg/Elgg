<?php

namespace Elgg;

use Elgg\Di\ServiceProvider;
use Elgg\Http\Request;
use stdClass;
use Zend\Mail\Transport\InMemory as InMemoryTransport;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * Constructs a test case with the given name.
	 * Boostraps testing environment
	 * 
	 * @param string $name
	 * @param array  $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '') {
		self::bootstrap();
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Boostraps test suite
	 *
	 * @global stdClass $CONFIG Global config
	 * @global stdClass $_ELGG  Global vars
	 * @return void
	 */
	public static function bootstrap() {

		date_default_timezone_set('America/Los_Angeles');

		error_reporting(E_ALL | E_STRICT);

		$config = new Config((object) self::getTestingConfigArray());
		$sp = new ServiceProvider($config);

		$sp->setValue('mailer', new InMemoryTransport());

		$sp->siteSecret->setTestingSecret('z1234567890123456789012345678901');

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();

		$app = new Application($sp);
		Application::$_instance = $app;

		// loadCore bails on repeated calls, so we need to manually inject this to make
		// sure it happens before each test.
		$app->loadCore();
		_elgg_services($sp);

		_elgg_filestore_boot();
	}

	/**
	 * Returns default testing configuration array
	 * @return array
	 */
	public static function getTestingConfigArray() {
		return [
			'Config_file' => false,
			'dbprefix' => 'elgg_',
			'boot_complete' => false,
			'wwwroot' => 'http://localhost/',
			'path' => __DIR__ . '/../../../../',
			'dataroot' => __DIR__ . '/../test_files/dataroot/',
			'cacheroot' => __DIR__ . '/../test_files/cacheroot/',
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
	}

	/**
	 * Get/set Config for testing purposes
	 *
	 * @staticvar \Elgg\Config $inst
	 * @param Config $config Config
	 * @return Config
	 */
	public static function config(Config $config = null) {
		if ($config) {
			_elgg_services()->setValue('config', $config);
		}
		return _elgg_services()->config;
	}

	/**
	 * Create an HTTP request
	 *
	 * @param string $uri             URI of the request
	 * @param string $method          HTTP method
	 * @param array  $parameters      Query/Post parameters
	 * @param int    $ajax            AJAX api version (0 for non-ajax)
	 * @param bool   $add_csrf_tokens Add CSRF tokens
	 * @return Request
	 */
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
		$site_url = elgg_get_site_url();
		$path = substr(elgg_normalize_url($uri), strlen($site_url));
		$path_key = Application::GET_PATH_KEY;

		if ($add_csrf_tokens) {
			$ts = time();
			$parameters['__elgg_ts'] = $ts;
			$parameters['__elgg_token'] = _elgg_services()->actions->generateActionToken($ts);
		}

		$request = Request::create("?$path_key=" . urlencode($path), $method, $parameters);

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

}

/**
 * We require BC to keep test cases extending PHPUnit_Framework_TestCase backward compatible.
 * @todo: remove in 3.0
 */
require dirname(dirname(__FILE__)) . '/bootstrap.php';