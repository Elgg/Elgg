<?php

namespace Elgg;

use DateTime;
use Elgg\Http\Request;
use Elgg\Mocks\Di\MockServiceProvider;
use PHPUnit_Framework_TestCase;
use stdClass;

abstract class UnitTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * @var UnitTestCase
	 */
	static $_instance;

	/**
	 * @var MockServiceProvider
	 */
	static $_mocks;

	/**
	 * Constructs a test case with the given name.
	 * Bootstraps testing environment
	 *
	 * @param string $name
	 * @param array  $data
	 * @param string $dataName
	 */
	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);
		self::$_instance = $this;
	}

	/**
	 * Returns current test instance
	 * @return UnitTestCase
	 */
	public static function getInstance() {
		if (!isset(self::$_instance)) {
			new static();
		}

		return self::$_instance;
	}

	/**
	 * Bootstrap a new unit testing app
	 *
	 * @return void
	 * @throws \Exception
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		try {
			self::bootstrap();
		} catch (\Throwable $e) {
			// PHPUnit can't deal with throwable until later versions
			throw new \Exception($e);
		}
	}

	/**
	 * Bootstraps test suite
	 *
	 * @global stdClass $CONFIG Global config
	 * @return void
	 */
	public static function bootstrap() {

		$settings_file = Application::elggDir()->getPath('/engine/tests/elgg-config/unit.php');
		\Elgg\Application::test($settings_file);

		// Invalidate memcache
		_elgg_get_memcache('new_entity_cache')->clear();

		self::$_mocks = null; // reset mocking service

		self::setupMockServices();
	}

	/**
	 * Returns default testing configuration array
	 * @return array
	 */
	public static function getTestingConfigArray() {
		return _elgg_config()->getValues();
	}

	/**
	 * Get/set Config for testing purposes
	 *
	 * @staticvar \Elgg\Config $inst
	 * @param Config $config Config
	 *
	 * @return Config
	 */
	public static function config(Config $config = null) {
		if ($config) {
			_elgg_services()->setValue('config', $config);
		}

		return _elgg_config();
	}

	/**
	 * Retuns mocking utility library
	 *
	 * @return MockServiceProvider
	 */
	public static function mocks() {
		if (!isset(self::$_mocks)) {
			self::$_mocks = new MockServiceProvider();
		}

		return self::$_mocks;
	}

	/**
	 * Substitute database dependent services with their doubles
	 * @return void
	 */
	public static function setupMockServices() {

		_elgg_services()->setValue('db', self::mocks()->db);
		_elgg_services()->setValue('entityTable', self::mocks()->entityTable);
		_elgg_services()->setValue('metadataTable', self::mocks()->metadataTable);
		_elgg_services()->setValue('annotations', self::mocks()->annotations);
		_elgg_services()->setValue('relationshipsTable', self::mocks()->relationshipsTable);
		_elgg_services()->setValue('accessCollections', self::mocks()->accessCollections);
		_elgg_services()->setValue('privateSettings', self::mocks()->privateSettings);
		_elgg_services()->setValue('subtypeTable', self::mocks()->subtypeTable);

		$dt = new DateTime();
		_elgg_services()->entityTable->setCurrentTime($dt);
		_elgg_services()->metadataTable->setCurrentTime($dt);
		_elgg_services()->relationshipsTable->setCurrentTime($dt);
		_elgg_services()->annotations->setCurrentTime($dt);
		_elgg_services()->usersTable->setCurrentTime($dt);

		_elgg_config()->site = self::mocks()->getSite([
			'url' => _elgg_config()->wwwroot,
			'name' => 'Testing Site',
			'description' => 'Testing Site',
		]);
	}

	/**
	 * Create an HTTP request
	 *
	 * @param string $uri             URI of the request
	 * @param string $method          HTTP method
	 * @param array  $parameters      Query/Post parameters
	 * @param int    $ajax            AJAX api version (0 for non-ajax)
	 * @param bool   $add_csrf_tokens Add CSRF tokens
	 *
	 * @return Request
	 */
	public static function prepareHttpRequest($uri = '', $method = 'GET', $parameters = [], $ajax = 0, $add_csrf_tokens = false) {
		$site_url = elgg_get_site_url();
		$path = '/' . ltrim(substr(elgg_normalize_url($uri), strlen($site_url)), '/');

		if ($add_csrf_tokens) {
			$ts = time();
			$parameters['__elgg_ts'] = $ts;
			$parameters['__elgg_token'] = _elgg_services()->actions->generateActionToken($ts);
		}

		$request = Request::create($path, $method, $parameters);

		$cookie_name = _elgg_config()->getCookieConfig()['session']['name'];
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

	/**
	 * Resolve test file name in /test_files
	 *
	 * @param string $filename File name
	 * @return string
	 */
	public function getTestFilePath($filename = '') {
		$filename = ltrim($filename, '/');

		$trailing = '';
		if (substr($filename, -1, 1) === '/') {
			// We want to preserve trailing slashes
			$trailing = '/';
		}

		return Application::elggDir()->getPath("engine/tests/phpunit/test_files/$filename") . $trailing;
	}

}
