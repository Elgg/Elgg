<?php
/**
 *
 */

namespace Elgg;

use Elgg\Database\Seeds\Seedable;
use Elgg\Di\ServiceProvider;
use Elgg\Project\Paths;
use PHPUnit_Framework_TestCase;

/**
 * Base test case abstraction
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase implements Seedable, Testable {

	use Testing;

	static $_instance;
	static $_settings;

	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);

		self::$_instance = $this;
	}

	public function __destruct() {
		self::$_instance = null;
	}

	/**
	 * Build a new testing application
	 * @return Application|false
	 */
	public static function createApplication() {
		return false;
	}

	/**
	 * Returns testing config
	 * @return Config
	 */
	public static function getTestingConfig() {
		if (!empty($_ENV['ELGG_SETTINGS_FILE'])) {
			$settings_path = $_ENV['ELGG_SETTINGS_FILE'];
			return Config::factory($settings_path);
		}

		return new Config([
			'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 't_i_elgg_',
			'dbname' => getenv('ELGG_DB_NAME') ? : '',
			'dbuser' => getenv('ELGG_DB_USER') ? : '',
			'dbpass' => getenv('ELGG_DB_PASS') ? : '',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',

			'memcache' => (bool) getenv('ELGG_MEMCACHE'),
			'memcache_servers' => [
				[getenv('ELGG_MEMCACHE_SERVER1_HOST'), getenv('ELGG_MEMCACHE_SERVER1_PORT')],
				[getenv('ELGG_MEMCACHE_SERVER2_HOST'), getenv('ELGG_MEMCACHE_SERVER2_PORT')],
			],
			'memcache_namespace_prefix' => getenv('ELGG_MEMCACHE_NAMESPACE_PREFIX') ? : 'elgg_mc_prefix_',

			// These are fixed, because tests rely on specific location of the dataroot for source files
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'dataroot' => Paths::elgg() . 'engine/tests/test_files/dataroot/',
			'cacheroot' => Paths::elgg() . 'engine/tests/test_files/cacheroot/',

			'system_cache_enabled' => false,
			'simplecache_enabled' => false,
			'boot_cache_ttl' => 0,

			'profile_files' => [],
			'group' => [],
			'group_tool_options' => [],

			'minusername' => 10,
			'profile_custom_fields' => [],
			'elgg_maintenance_mode' => false,

			'icon_sizes' => [
				'topbar' => [
					'w' => 16,
					'h' => 16,
					'square' => true,
					'upscale' => true
				],
				'tiny' => [
					'w' => 25,
					'h' => 25,
					'square' => true,
					'upscale' => true
				],
				'small' => [
					'w' => 40,
					'h' => 40,
					'square' => true,
					'upscale' => true
				],
				'medium' => [
					'w' => 100,
					'h' => 100,
					'square' => true,
					'upscale' => true
				],
				'large' => [
					'w' => 200,
					'h' => 200,
					'square' => false,
					'upscale' => false
				],
				'master' => [
					'w' => 550,
					'h' => 550,
					'square' => false,
					'upscale' => false
				],
			],
			'debug' => 'NOTICE',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() {

		Application::setInstance(null);

		$app = static::createApplication();
		if (!$app) {
			$this->markTestSkipped();
		}

		$dt = new \DateTime();

		$app->_services->entityTable->setCurrentTime($dt);
		$app->_services->metadataTable->setCurrentTime($dt);
		$app->_services->relationshipsTable->setCurrentTime($dt);
		$app->_services->annotations->setCurrentTime($dt);
		$app->_services->usersTable->setCurrentTime($dt);

		$app->_services->session->removeLoggedInUser();
		$app->_services->session->setIgnoreAccess(false);
		access_show_hidden_entities(false);

		// Make sure the application has been bootstrapped correctly
		$this->assertInstanceOf(Application::class, elgg());
		$this->assertInstanceOf(ServiceProvider::class, $app->_services);
		$this->assertInstanceOf(Config::class, $app->_services->config);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown() {

		// We do not want overflowing ignored access
		$this->assertFalse((bool) elgg_get_ignore_access());

		// We do not want overflowing show hidden status
		$this->assertFalse((bool) access_get_show_hidden_status());

		// Tests should run without a logged in user
		$this->assertFalse((bool) elgg_is_logged_in());

		// free up some memory
		$refl = new \ReflectionObject($this);
		foreach ($refl->getProperties() as $prop) {
			if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
				$prop->setAccessible(true);
				$prop->setValue($this, null);
			}
		}
	}

	/**
	 * Called after setUp() method and can be used by test cases to setup their test logic
	 * @return mixed
	 */
	abstract function up();

	/**
	 * Called before tearDown() method and can be used by test cases to clear their test logic
	 * @return mixed
	 */
	abstract function down();

}