<?php

namespace Elgg;

use Elgg\Database\DbConfig;
use Elgg\Di\ServiceProvider;
use ElggSession;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Laminas\Mail\Transport\InMemory;

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

		$custom_config_values = (array) elgg_extract('custom_config_values', $params, []);
		unset($params['custom_config_values']);

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
		foreach ($custom_config_values as $key => $value) {
			$config->$key = $value;
		}
		
		$sp = new ServiceProvider($config);
		$config->system_cache_enabled = true;
		$config->boot_cache_ttl = 600;
		$config->plugins_path = elgg_extract('plugins_path', $params);
		
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

		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		$app->_services->setValue('cli_output', $cli_output);

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
		$app->_services->events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
		$app->_services->events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');

		$app->bootCore();
		
		// set correct base classes for testing purposes
		$app->_services->entityTable->setEntityClass('object', 'plugin', \Elgg\Mocks\ElggPlugin::class);

		if (!$isolate) {
			self::$_testing_app = $app;
		}
		
		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tearDownAfterClass(): void {
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
	final protected function setUp(): void {
		parent::setUp();

		$plugin_id = $this->getPluginID();
		if (!empty($plugin_id)) {
			$plugin = elgg_get_plugin_from_id($plugin_id);

			if (!$plugin || !$plugin->isActive()) {
				$this->markTestSkipped("Plugin '{$plugin_id}' isn't active, skipped test");
			}
		}
		
		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown(): void {
		$this->down();

		parent::tearDown();
	}
}
