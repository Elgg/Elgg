<?php

namespace Elgg;

use Elgg\Database\DbConfig;
use Elgg\Di\ServiceProvider;
use ElggSession;
use Psr\Log\LogLevel;
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
	public static function createApplication(array $params = []) {

		$isolate = elgg_extract('isolate', $params, false);
		unset($params['isolate']);

		if (isset(self::$_testing_app) && !$isolate) {
			$app = self::$_testing_app;

			Application::setInstance($app);

			// Invalidate caches
			$app->_services->dataCache->clear();
			$app->_services->sessionCache->clear();

			return $app;
		}

		Application::setInstance(null);

		$config = self::getTestingConfig();
		$sp = new ServiceProvider($config);
		$config->system_cache_enabled = true;
		$config->boot_cache_ttl = 600;

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();

		$sp->setFactory('session', function () {
			return ElggSession::getMock();
		});

		$sp->setFactory('mailer', function () {
			return new InMemory();
		});

		$app = Application::factory(array_merge([
			'config' => $config,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		try {
			$app->_services->db->getConnection(DbConfig::WRITE);
		} catch (\Exception $ex) {
			return false;
		}

		Application::setInstance($app);

		$app->_services->setValue('logger', Logger::factory());

		if (in_array('--verbose', $_SERVER['argv'])) {
			$app->_services->logger->setLevel(LogLevel::DEBUG);
		} else {
			$app->_services->logger->setLevel(LogLevel::ERROR);
		}

		// Invalidate caches
		$app->_services->dataCache->clear();
		$app->_services->sessionCache->clear();

		// turn off system log
		$app->_services->events->unregisterHandler('all', 'all', 'system_log_listener');
		$app->_services->events->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		$app->bootCore();

		self::$_testing_app = $app;

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
		foreach (_elgg_services()->logger->getHandlers() as $handler) {
			if (is_callable([$handler, 'close'])) {
				$handler->close();
			}
		}
		parent::tearDownAfterClass();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {

		parent::setUp();

		$app = Application::getInstance();

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

		$app = Application::getInstance();

		if ($this instanceof LegacyIntegrationTestCase) {
			$app->_services->session->removeLoggedInUser();
		}

		parent::tearDown();
	}
}
