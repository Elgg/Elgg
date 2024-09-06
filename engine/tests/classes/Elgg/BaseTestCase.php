<?php

namespace Elgg;

use Elgg\Database\Seeds\Seedable;
use Elgg\Di\InternalContainer;
use Elgg\Plugins\PluginTesting;
use Elgg\Project\Paths;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Phpfastcache\CacheManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Base test case abstraction
 */
abstract class BaseTestCase extends TestCase implements Seedable, Testable {

	use Testing;
	use PluginTesting;
	use EventTesting;
	use MessageTesting;

	/**
	 * Build a new testing application
	 *
	 * @param array $params Application factory parameters
	 * @return Application|false
	 */
	public static function createApplication(array $params = []) {
		return false;
	}

	/**
	 * Returns testing config
	 * @return Config
	 */
	public static function getTestingConfig() {
		return new Config([
			'dbprefix' => getenv('ELGG_DB_PREFIX') !== false ? getenv('ELGG_DB_PREFIX') : 'c_i_elgg_',
			'dbname' => getenv('ELGG_DB_NAME') ?: '',
			'dbuser' => getenv('ELGG_DB_USER') ?: '',
			'dbpass' => getenv('ELGG_DB_PASS') ?: '',
			'dbhost' => getenv('ELGG_DB_HOST') ?: 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ?: 3306,
			'dbencoding' => getenv('ELGG_DB_ENCODING') ?: 'utf8mb4',

			'memcache' => (bool) getenv('ELGG_MEMCACHE'),
			'memcache_servers' => [
				[
					'host' => getenv('ELGG_MEMCACHE_SERVER1_HOST'),
					'port' => (int) getenv('ELGG_MEMCACHE_SERVER1_PORT'),
				],
			],
			'memcache_namespace_prefix' => getenv('ELGG_MEMCACHE_NAMESPACE_PREFIX') ?: 'elgg_mc_prefix_',

			'redis' => (bool) getenv('ELGG_REDIS'),
			'redis_servers' => [
				[
					'host' => getenv('ELGG_REDIS_SERVER1_HOST'),
					'port' => (int) getenv('ELGG_REDIS_SERVER1_PORT'),
				],
			],

			// These are fixed, because tests rely on specific location of the dataroot for source files
			'wwwroot' => getenv('ELGG_WWWROOT') ?: 'http://localhost/',
			'dataroot' => Paths::elgg() . 'engine/tests/test_files/dataroot/',
			'cacheroot' => Paths::elgg() . 'engine/tests/test_files/cacheroot/',
			'assetroot' => Paths::elgg() . 'engine/tests/test_files/assetroot/',
			'plugins_path' => Paths::project() . 'mod/',
			'seeder_local_image_folder' => getenv('ELGG_SEEDER_LOCAL_IMAGE_FOLDER') ?: Paths::elgg() . '.scripts/seeder/images/',
			
			'system_cache_enabled' => false,
			'simplecache_enabled' => false,
			'boot_cache_ttl' => 0,
			'lastcache' => time(),
			
			'minusername' => 10,
			'profile_custom_fields' => [],
			'elgg_maintenance_mode' => false,
			'testing_mode' => true,
			'email_html_part' => false,

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
					'square' => true,
					'upscale' => true
				],
				'master' => [
					'w' => 10240,
					'h' => 10240,
					'square' => false,
					'upscale' => false,
					'crop' => false,
				],
			],
			'debug' => LogLevel::NOTICE,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void {

		Application::setInstance(null);

		$app = static::createApplication();
		if (!$app) {
			$this->markTestSkipped();
		}

		$app->internal_services->session_manager->removeLoggedInUser();
		$app->internal_services->session_manager->setIgnoreAccess(false);
		$app->internal_services->session_manager->setDisabledEntityVisibility(false);
		
		// make sure the logger is enabled before each test, in case a previous test disabled it but forgot to re-enable it
		$app->internal_services->logger->enable();

		// Make sure the application has been bootstrapped correctly
		$this->assertInstanceOf(InternalContainer::class, $app->internal_services, __METHOD__ . ': InternalContainer not bootstrapped');
		$this->assertInstanceOf(Config::class, $app->internal_services->config, __METHOD__ . ': Config not bootstrapped');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void {

		// We do not want overflowing ignored access
		$this->assertFalse((bool) _elgg_services()->session_manager->getIgnoreAccess(), __METHOD__ . ': ignored access not reset');

		// We do not want overflowing show hidden status
		$this->assertFalse((bool) _elgg_services()->session_manager->getDisabledEntityVisibility(), __METHOD__ . ': hidden entities not reset');

		// Tests should run without a logged in user
		if (_elgg_services()->session_manager->isLoggedIn()) {
			_elgg_services()->session_manager->removeLoggedInUser();
		}
		$this->assertFalse((bool) _elgg_services()->session_manager->isLoggedIn(), __METHOD__ . ': there should be no logged in user');
		
		// cleanup admin user
		$admin = $this->_testing_admin;
		unset($this->_testing_admin);
		if ($admin instanceof \ElggUser) {
			$admin->delete(true, true);
		}
		
		// clear all message registers
		if (_elgg_services()->session->isStarted()) {
			_elgg_services()->system_messages->dumpRegister();
		}
		
		// close the database connections to prevent 'too many connections'
		_elgg_services()->db->closeConnections();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function tearDownAfterClass(): void {
		// performing some additional cleanup as we can't rely on php garbage collection
		foreach (CacheManager::getInstances() as $instance) {
			if ($instance instanceof \Phpfastcache\Drivers\Memcached\Driver) {
				$memcached_software = $this->getInaccessableProperty($instance, 'instance');
				$memcached_software->flush();
				$memcached_software->quit();
			} elseif ($instance instanceof \Phpfastcache\Drivers\Redis\Driver) {
				$redis_software = $this->getInaccessableProperty($instance, 'instance');
				$redis_software->flushAll();
				$redis_software->close();
			}
		}
		
		CacheManager::clearInstances();
		
		parent::tearDownAfterClass();
	}
	
	/**
	 * Called after setUp() method and can be used by test cases to setup their test logic
	 * @return mixed
	 */
	public function up() {
	}

	/**
	 * Called before tearDown() method and can be used by test cases to clear their test logic
	 * @return mixed
	 */
	public function down() {
	}

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 * @return \Doctrine\DBAL\Platforms\AbstractPlatform|MockObject
	 */
	public function getDatabasePlatformMock() {
		$mock = $this->getMockBuilder('Doctrine\DBAL\Platforms\MySQLPlatform')
			->onlyMethods([
				'getTruncateTableSQL',
			])
			->getMock();

		$mock->expects($this->any())
			->method('getTruncateTableSQL')
			->with($this->anything())
			->willReturn('#TRUNCATE {table}');

		return $mock;
	}

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 * @return \Doctrine\DBAL\Connection|MockObject
	 */
	public function getConnectionMock() {
		$mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
			->disableOriginalConstructor()
			->onlyMethods(
				[
					'beginTransaction',
					'commit',
					'rollback',
					'prepare',
					'executeQuery',
					'executeStatement',
					'getDatabasePlatform',
					'lastInsertId',
					'quote',
				]
			)
			->getMock();
			
		$mock->expects($this->any())
			->method('getDatabasePlatform')
			->willReturn($this->getDatabasePlatformMock());

		return $mock;
	}
	
	/**
	 * Tests if two inputs are equal. If the inputs are \ElggData object they will be transformed to plain objects
	 *
	 * @param mixed  $expected Expected result
	 * @param mixed  $actual   Actual results
	 * @param string $message  Message to report
	 */
	public static function assertElggDataEquals(mixed $expected, mixed $actual, string $message = ''): void {
		if ($expected instanceof \ElggData) {
			$expected = $expected->toObject();
		}
		
		if ($actual instanceof \ElggData) {
			$actual = $actual->toObject();
		}
		
		parent::assertEquals($expected, $actual, $message);
	}
	
	/**
	 * Invokes an inaccessable method
	 *
	 * @param mixed  $argument object or class to invoke on
	 * @param string $method   method to invoke
	 * @param mixed  ...$args  zero or more arguments to pass to the method
	 *
	 * @return mixed
	 */
	protected static function invokeInaccessableMethod($argument, string $method, ...$args) {
		$reflector = new \ReflectionClass($argument);
		
		return $reflector->getMethod($method)->invoke($argument, ...$args);
	}
	
	/**
	 * Retrieves an inaccessable property
	 *
	 * @param mixed  $argument object or class to get the property from
	 * @param string $property name of the property
	 *
	 * @return mixed
	 */
	protected static function getInaccessableProperty($argument, string $property) {
		$reflector = new \ReflectionClass($argument);
		
		return $reflector->getProperty($property)->getValue($argument);
	}
}
