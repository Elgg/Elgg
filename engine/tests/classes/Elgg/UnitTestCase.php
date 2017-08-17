<?php

namespace Elgg;

use DateTime;
use Elgg\Di\ServiceProvider;
use Elgg\Mocks\Di\MockServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Unit test abstraction class
 *
 * Extend this class to run PHPUnit tests without a database connection
 */
abstract class UnitTestCase extends BaseTestCase {

	/**
	 * {@inheritdoc}
	 */
	public static function getSettingsPath() {
		return Application::elggDir()->getPath('/engine/tests/elgg-config/unit.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getResettableServices() {
		return [
			'accessCollections',
			'accessCache',
			'actions',
			'adminNotices',
			'ajax',
			'amdConfig',
			'annotations',
			'autoP',
			'autoloadManager',
			'batchUpgrader',
			'cacheHandler',
			'crypto',
			'context',
			'db',
			'dbConfig',
			'emails',
			'entityCache',
			'entityPreloader',
			'entityTable',
			'forms',
			'handlers',
			'hooks',
			'iconService',
			'input',
			'imageService',
			'logger',
			'mailer',
			'metadataCache',
			'memcacheStashPool',
			'metadataTable',
			'mutex',
			'notifications',
			'nullCache',
			'persistentLogin',
			'plugins',
			'pluginSettingsCache',
			'privateSettings',
			'publicDb',
			'queryCounter',
			'redirects',
			'request',
			'responseFactory',
			'relationshipsTable',
			'router',
			'serveFileHandler',
			'session',
			'siteSecret',
			'stickyForms',
			'subtypeTable',
			'systemCache',
			'systemMessages',
			'table_columns',
			'translator',
			'upgrades',
			'upgradeLocator',
			'uploads',
			'userCapabilities',
			'usersTable',
			'views',
			'viewCacher',
			'widgets',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function bootstrap($force = false) {
		$app = Application::$_instance;
		if ($force || $app->_services->config->elgg_settings_file != self::getSettingsPath()) {
			// When settings differ, bootstrap a new app
			return parent::bootstrap();
		} else {
			// Otherwise just reset it
			self::reset();
			return $app;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication() {

		$settings_path = self::getSettingsPath();
		$config = Config::fromFile($settings_path);

		$sp = new MockServiceProvider($config);

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		]);

		// persistentLogin service needs this set to instantiate without calling DB
		$app->_services->config->getCookieConfig();

		Application::setInstance($app);

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		_elgg_services()->logger->notice('Bootstrapped a new Application instance from settings in ' . $settings_path);

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {
		parent::setUp();

		_elgg_services()->config->site = $this->createSite([
			'url' => _elgg_config()->wwwroot,
			'name' => 'Testing Site',
			'description' => 'Testing Site',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * {@inheritdoc}
	 */
	public function createUser(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_user';

		return _elgg_services()->entityTable->setup(null, 'user', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createObject(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_object';

		return _elgg_services()->entityTable->setup(null, 'object', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createGroup(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_group';

		return _elgg_services()->entityTable->setup(null, 'group', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createSite(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_site';

		return _elgg_services()->entityTable->setup(null, 'site', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function assertPreConditions() {
		parent::assertPreConditions();

		$this->assertInstanceOf(MockServiceProvider::class,  _elgg_services());
		$this->assertInstanceOf(\Elgg\Mocks\Database::class, _elgg_services()->db);
	}

	protected function assertPostConditions() {
		parent::assertPostConditions();

		$this->assertInstanceOf(MockServiceProvider::class,  _elgg_services());
		$this->assertInstanceOf(\Elgg\Mocks\Database::class, _elgg_services()->db);
	}

}

