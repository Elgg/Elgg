<?php
/**
 *
 */

namespace Elgg;

use Elgg\Database\Seeds\Seedable;
use Elgg\Di\ServiceProvider;
use Elgg\Plugins\PluginTesting;
use Elgg\Project\Paths;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Base test case abstraction
 */
abstract class BaseTestCase extends TestCase implements Seedable, Testable {

	use Testing;
	use PluginTesting;
	use HookTesting;
	use EventTesting;
	use MessageTesting;

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
		if (!empty($_ENV['ELGG_SETTINGS_FILE'])) {
			$settings_path = $_ENV['ELGG_SETTINGS_FILE'];

			return Config::factory($settings_path);
		}

		return new Config([
			'dbprefix' => getenv('ELGG_DB_PREFIX') ? : 'c_i_elgg_',
			'dbname' => getenv('ELGG_DB_NAME') ? : '',
			'dbuser' => getenv('ELGG_DB_USER') ? : '',
			'dbpass' => getenv('ELGG_DB_PASS') ? : '',
			'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',
			'dbport' => getenv('ELGG_DB_PORT') ? : 3306,
			'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',

			'memcache' => (bool) getenv('ELGG_MEMCACHE'),
			'memcache_servers' => [
				[getenv('ELGG_MEMCACHE_SERVER1_HOST'), getenv('ELGG_MEMCACHE_SERVER1_PORT')],
				[getenv('ELGG_MEMCACHE_SERVER2_HOST'), getenv('ELGG_MEMCACHE_SERVER2_PORT')],
			],
			'memcache_namespace_prefix' => getenv('ELGG_MEMCACHE_NAMESPACE_PREFIX') ? : 'elgg_mc_prefix_',

			'redis' => (bool) getenv('ELGG_REDIS'),
			'redis_servers' => [
				[getenv('ELGG_REDIS_SERVER1_HOST'), getenv('ELGG_REDIS_SERVER1_PORT')],
			],

			// These are fixed, because tests rely on specific location of the dataroot for source files
			'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
			'dataroot' => Paths::elgg() . 'engine/tests/test_files/dataroot/',
			'cacheroot' => Paths::elgg() . 'engine/tests/test_files/cacheroot/',
			'assetroot' => Paths::elgg() . 'engine/tests/test_files/assetroot/',
			'seeder_local_image_folder' => getenv('ELGG_SEEDER_LOCAL_IMAGE_FOLDER') ? : Paths::elgg() . '.scripts/seeder/images/',
			
			'system_cache_enabled' => false,
			'simplecache_enabled' => false,
			'boot_cache_ttl' => 0,
			'lastcache' => time(),
			'elgg_config_locks' => false,
			
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

		$dt = new \DateTime();

		$app->_services->entityTable->setCurrentTime($dt);
		$app->_services->metadataTable->setCurrentTime($dt);
		$app->_services->relationshipsTable->setCurrentTime($dt);
		$app->_services->annotationsTable->setCurrentTime($dt);
		$app->_services->usersTable->setCurrentTime($dt);

		$app->_services->session->removeLoggedInUser();
		$app->_services->session->setIgnoreAccess(false);
		$app->_services->session->setDisabledEntityVisibility(false);

		// Make sure the application has been bootstrapped correctly
		$this->assertInstanceOf(ServiceProvider::class, $app->_services, __METHOD__ . ': ServiceProvider not bootstrapped');
		$this->assertInstanceOf(Config::class, $app->_services->config, __METHOD__ . ': Config not bootstrapped');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void {

		// We do not want overflowing ignored access
		$this->assertFalse((bool) _elgg_services()->session->getIgnoreAccess(), __METHOD__ . ': ignored access not reset');

		// We do not want overflowing show hidden status
		$this->assertFalse((bool) _elgg_services()->session->getDisabledEntityVisibility(), __METHOD__ . ': hidden entities not reset');

		// Tests should run without a logged in user
		$this->assertFalse((bool) _elgg_services()->session->isLoggedIn(), __METHOD__ . ': there should be no logged in user');
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

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 * @return \Doctrine\DBAL\Platforms\AbstractPlatform|MockObject
	 */
	public function getDatabasePlatformMock() {
		$mock = $this->getAbstractMock(
			'Doctrine\DBAL\Platforms\AbstractPlatform',
			[
				'getName',
				'getTruncateTableSQL',
			]
		);

		$mock->expects($this->any())
			->method('getName')
			->will($this->returnValue('mysql'));

		$mock->expects($this->any())
			->method('getTruncateTableSQL')
			->with($this->anything())
			->will($this->returnValue('#TRUNCATE {table}'));

		return $mock;
	}

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 * @return \Doctrine\DBAL\Connection|MockObject
	 */
	public function getConnectionMock() {
		$mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
			->disableOriginalConstructor()
			->setMethods(
				[
					'beginTransaction',
					'commit',
					'rollback',
					'prepare',
					'query',
					'executeQuery',
					'executeStatement',
					'executeUpdate',
					'getDatabasePlatform',
					'lastInsertId',
					'getExpressionBuilder',
					'quote',
				]
			)
			->getMock();

		$mock->expects($this->any())
			->method('prepare')
			->will($this->returnValue($this->getStatementMock()));

//		$mock->expects($this->any())
//			->method('query')
//			->will($this->returnValue($this->getStatementMock()));

		$mock->expects($this->any())
			->method('getDatabasePlatform')
			->will($this->returnValue($this->getDatabasePlatformMock()));

		return $mock;
	}

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 * @return \Doctrine\DBAL\Driver\Statement|MockObject
	 */
	public function getStatementMock() {
		$mock = $this->getAbstractMock(
			'Doctrine\DBAL\Driver\Statement',
			[
				'bindValue',
				'execute',
				'rowCount',
				'fetchColumn',
			]
		);

		$mock->expects($this->any())
			->method('fetchColumn')
			->will($this->returnValue(1));

		return $mock;
	}

	/**
	 * @source https://gist.github.com/gnutix/7746893
	 *
	 * @param string $class   The class name
	 * @param array  $methods The available methods
	 *
	 * @return MockObject
	 */
	protected function getAbstractMock($class, array $methods) {
		return $this->getMockForAbstractClass(
			$class,
			[],
			'',
			true,
			true,
			true,
			$methods,
			false
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false): void {
		if ($expected instanceof \ElggData) {
			$expected = $expected->toObject();
		}

		if ($actual instanceof \ElggData) {
			$actual = $actual->toObject();
		}

		parent::assertEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
	}

}
