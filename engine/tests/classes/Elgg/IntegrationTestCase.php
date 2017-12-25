<?php

namespace Elgg;

use Elgg\Di\ApplicationContainer;
use Elgg\Di\ServiceProvider;
use Elgg\Plugins\PluginTesting;
use ElggSession;
use Symfony\Component\Console\Output\ConsoleOutput;
use Zend\Mail\Transport\InMemory;

/**
 * Integration test abstraction
 *
 * Extend this class to run tests against an actual database
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseTestCase {

	use TestSeeding;

	static $_testing_app;

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication($isolate = false) {

		if (isset(self::$_testing_app) && !$isolate) {
			$app = self::$_testing_app;

			ApplicationContainer::setInstance($app);

			// Invalidate caches
			$app->application->_services->dataCache->clear();
			$app->application->_services->sessionCache->clear();

			return $app->application;
		}

		Application::destroy();

		$config = self::getTestingConfig();
		$sp = new ServiceProvider($config);
		$config->system_cache_enabled = true;
		$config->boot_cache_ttl = 10;

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();

		$sp->setFactory('session', function () {
			return ElggSession::getMock();
		});

		$sp->setFactory('mailer', function () {
			return new InMemory();
		});

		$app = Application::factory([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
			'kernel' => function(ApplicationContainer $c) {
				return new TestingKernel($c->application, $c->cacheHandler, $c->serveFileHandler);
			},
		]);

		if (!$app->getDbConnection()) {
			return false;
		}

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		// Invalidate caches
		$app->_services->dataCache->clear();
		$app->_services->sessionCache->clear();

		// turn off system log
		$app->_services->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
		$app->_services->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		$app->boot();

		self::$_testing_app = ApplicationContainer::getInstance();

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {

		parent::setUp();

		$app = ApplicationContainer::getInstance()->application;

		$plugin_id = $this->getPluginID();
		if (!empty($plugin_id)) {
			$plugin = elgg_get_plugin_from_id($plugin_id);

			if (!$plugin || !$plugin->isActive()) {
				$this->markTestSkipped("Plugin '{$plugin_id}' isn't active, skipped test");
			}
		}

		// legacy support, need logged in user
		if ($this instanceof LegacyIntegrationTestCase) {
			$app->_services->session->setLoggedInUser($this->getAdmin());
		}

		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown() {
		$this->down();

		/**
		 * @todo: This is bad because this overflows into other states
		 *      But until there is a sane entity delete strategy this takes too long
		 */
		//$this->clearSeeds();

		$app = ApplicationContainer::getInstance()->application;

		if ($this instanceof LegacyIntegrationTestCase) {
			$app->_services->session->removeLoggedInUser();
		}

		parent::tearDown();
	}
}
