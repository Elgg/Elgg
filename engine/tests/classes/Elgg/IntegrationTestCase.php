<?php

namespace Elgg;

use Elgg\Database\DbConfig;
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
			$app->internal_services->dataCache->clear();
			$app->internal_services->sessionCache->clear();

			return $app;
		}

		Application::setInstance(null);

		$config = self::getTestingConfig();
		foreach ($custom_config_values as $key => $value) {
			$config->$key = $value;
		}

		$config->system_cache_enabled = true;
		$config->boot_cache_ttl = 600;
		$config->plugins_path = elgg_extract('plugins_path', $params);
		$config->getCookieConfig();

		$app = Application::factory(array_merge([
			'config' => $config,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));
		
		$app->setGlobalConfig($app);
		
		$app->internal_services->set('session', function () {
			return ElggSession::getMock();
		});

		$app->internal_services->set('mailer', function () {
			return new InMemory();
		});

		try {
			$app->internal_services->db->getConnection(DbConfig::WRITE);
		} catch (\Exception $ex) {
			return false;
		}

		Application::setInstance($app);

		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		$app->internal_services->set('cli_output', $cli_output);

		$app->internal_services->set('logger', Logger::factory());

		if (in_array('--verbose', $_SERVER['argv'])) {
			$app->internal_services->logger->setLevel(LogLevel::DEBUG);
		} else {
			$app->internal_services->logger->setLevel(LogLevel::ERROR);
		}

		// Invalidate caches
		$app->internal_services->dataCache->clear();
		$app->internal_services->sessionCache->clear();

		// prevent loading of 'active' plugins from database if loading application with a custom plugins path
		if (isset($params['plugins_path'])) {
			$app->setBootStatus('plugins_boot_completed', true);
		}
		
		$app->bootCore();
		
		// turn off system log
		$app->internal_services->events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
		$app->internal_services->events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');
		
		// set custom config values again (as they might be overriden by DB config values
		foreach ($custom_config_values as $key => $value) {
			$app->internal_services->config->$key = $value;
		}
		
		// set correct base classes for testing purposes
		$app->internal_services->entityTable->setEntityClass('object', 'plugin', \Elgg\Mocks\ElggPlugin::class);

		// register object/commentable as a subtype that is always commentable
		elgg_entity_enable_capability('object', 'commentable', 'commentable');
		
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
		
		$this->clearSeeds();

		parent::tearDown();
	}
}
